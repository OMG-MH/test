# handlers.py
import telebot
from telebot import types
import threading
import time

from config import ADMIN_NAME1, ADMIN_NAME2, ADMIN_NAME3, ADMIN_NAME4
from config import ADMIN_ID1, ADMIN_ID2, ADMIN_ID3, ADMIN_ID4, QUICK_REPLY_LABEL1, QUICK_REPLY_LABEL2, QUICK_REPLY_LABEL3
from utils import (add_pending_entry, remove_pending_entry, get_pending_entry,
                   add_assignment, remove_assignment, get_assignment,
                   extract_user_id, admin_keyboard)


def register_handlers(bot: telebot.TeleBot):

    @bot.message_handler(
        func=lambda m: m.from_user.id in [ADMIN_ID1, ADMIN_ID2, ADMIN_ID3, ADMIN_ID4] and
        m.text in [QUICK_REPLY_LABEL1, QUICK_REPLY_LABEL2, QUICK_REPLY_LABEL3
                   ] and m.reply_to_message is None)
    def warn_no_reply(message):
        bot.send_message(
            message.chat.id,
            "❌ استخدم الزر كـرد على رسالة مستخدم لإرسال الملصق أو الشرح.",
            reply_markup=admin_keyboard(QUICK_REPLY_LABEL1, QUICK_REPLY_LABEL2,
                                        QUICK_REPLY_LABEL3))

    @bot.message_handler(
        func=lambda m: m.from_user.id in [ADMIN_ID1, ADMIN_ID2, ADMIN_ID3, ADMIN_ID4
                                          ] and m.reply_to_message is not None,
        content_types=['text'])
    def admin_reply(message):
        if not message.reply_to_message:
            return

        original = message.reply_to_message.text or message.reply_to_message.caption or ""
        target_id = extract_user_id(original)

        if not target_id:
            bot.send_message(message.chat.id,
                             "❌ لا يمكن تحديد المستخدم المرتبط بهذه الرسالة.",
                             reply_markup=admin_keyboard(
                                 QUICK_REPLY_LABEL1, QUICK_REPLY_LABEL2,
                                 QUICK_REPLY_LABEL3))
            return

        if target_id in [ADMIN_ID1, ADMIN_ID2, ADMIN_ID3, ADMIN_ID4]:
            bot.send_message(message.chat.id,
                             "❌ لا يمكن الرد على نفسك.",
                             reply_markup=admin_keyboard(
                                 QUICK_REPLY_LABEL1, QUICK_REPLY_LABEL2,
                                 QUICK_REPLY_LABEL3))
            return

        txt = message.text or ""

        if txt == QUICK_REPLY_LABEL1:
            try:
                with open("sticker.webp", "rb") as st:
                    bot.send_sticker(target_id, st)
            except Exception:
                try:
                    bot.send_message(target_id, "✅ تم الشحن. شكراً لك.")
                except:
                    pass
            remove_assignment(target_id)
            bot.send_message(message.chat.id,
                             "✅ تم إنهاء الربط مع المستخدم.",
                             reply_markup=admin_keyboard(
                                 QUICK_REPLY_LABEL1, QUICK_REPLY_LABEL2,
                                 QUICK_REPLY_LABEL3))
            return

        if txt == QUICK_REPLY_LABEL2:
            bot.send_message(
                target_id,
                "عشان نسجل باليوزر والباسوورد المسجلين في حسابك على تيك توك...\n\n1- افتح التطبيق وادخل على الملف الشخصي...\n[أكمل الشرح هنا]",
            )
            return

        if txt == QUICK_REPLY_LABEL3:
            bot.send_message(
                target_id,
                "طريقة التسجيل بالباركود:\n1- افتح الملف الشخصي\n2- اضغط على QR Code\n3- اعمل مسح للباركود المرسل.",
            )
            return

        try:
            bot.send_message(target_id,
                             f"<b><u>{txt}</u></b>",
                             parse_mode="HTML")

            countdown_msg = bot.send_message(message.chat.id,
                                             "✅ تم إرسال الرد. (5)")

            def countdown_and_delete(msg_id, chat_id):
                for i in range(5, 0, -1):
                    try:
                        bot.edit_message_text(f"✅ تم إرسال الرد. ({i})",
                                              chat_id, msg_id)
                    except:
                        pass
                    time.sleep(1)
                try:
                    bot.delete_message(chat_id, msg_id)
                except:
                    pass

            threading.Thread(target=countdown_and_delete,
                             args=(countdown_msg.message_id,
                                   countdown_msg.chat.id)).start()

        except Exception as e:
            bot.send_message(message.chat.id,
                             f"❌ خطأ عند إرسال الرد: {e}",
                             reply_markup=admin_keyboard(
                                 QUICK_REPLY_LABEL1, QUICK_REPLY_LABEL2,
                                 QUICK_REPLY_LABEL3))

    @bot.message_handler(
        func=lambda m: m.from_user.id in [ADMIN_ID1, ADMIN_ID2, ADMIN_ID3, ADMIN_ID4
                                          ] and m.reply_to_message is not None,
        content_types=['photo'])
    def admin_reply_photo(message):
        original = message.reply_to_message.text or message.reply_to_message.caption or ""
        target_id = extract_user_id(original)

        if not target_id:
            bot.send_message(message.chat.id,
                             "❌ لا يمكن تحديد المستخدم المرتبط بهذه الرسالة.",
                             reply_markup=admin_keyboard(
                                 QUICK_REPLY_LABEL1, QUICK_REPLY_LABEL2,
                                 QUICK_REPLY_LABEL3))
            return

        if target_id in [ADMIN_ID1, ADMIN_ID2, ADMIN_ID3, ADMIN_ID4]:
            bot.send_message(message.chat.id,
                             "❌ لا يمكن الرد على نفسك.",
                             reply_markup=admin_keyboard(
                                 QUICK_REPLY_LABEL1, QUICK_REPLY_LABEL2,
                                 QUICK_REPLY_LABEL3))
            return

        caption = message.caption or ""
        photo = message.photo[-1].file_id  # أكبر دقة

        try:
            bot.send_photo(target_id, photo, caption=caption)
            try:
                countdown_msg = bot.send_message(
                    message.chat.id, "✅ تم إرسال الصورة للمستخدم. (5)")

                def countdown_and_delete(msg_id, chat_id):
                    for i in range(5, 0, -1):
                        try:
                            bot.edit_message_text(
                                f"✅ تم إرسال الصورة للمستخدم. ({i})", chat_id,
                                msg_id)
                        except:
                            pass
                        time.sleep(1)
                    try:
                        bot.delete_message(chat_id, msg_id)
                    except:
                        pass

                threading.Thread(target=countdown_and_delete,
                                 args=(countdown_msg.message_id,
                                       countdown_msg.chat.id)).start()

            except Exception as e:
                bot.send_message(message.chat.id,
                                 f"❌ خطأ عند إرسال الرد: {e}",
                                 reply_markup=admin_keyboard(
                                     QUICK_REPLY_LABEL1, QUICK_REPLY_LABEL2,
                                     QUICK_REPLY_LABEL3))
            # bot.send_message(message.chat.id, "✅ تم إرسال الصورة للمستخدم.")
        except Exception as e:
            bot.send_message(message.chat.id,
                             f"❌ خطأ أثناء إرسال الصورة: {e}",
                             reply_markup=admin_keyboard(
                                 QUICK_REPLY_LABEL1, QUICK_REPLY_LABEL2,
                                 QUICK_REPLY_LABEL3))

    @bot.message_handler(commands=['start'])
    def start(message):
        uid = message.from_user.id
        args = message.text.split(" ")
        ref_code = args[1] if len(args) > 1 else None

        if uid in [ADMIN_ID1, ADMIN_ID2, ADMIN_ID3, ADMIN_ID4]:
            bot.send_message(
                message.chat.id,
                "مرحباً بك! لقد أصبحت الآن أدمن في البوت 😉\n"
                "يمكنك استخدام الزر في الأسفل لإخبار المستخدم بأنه تم الشحن.",
                reply_markup=admin_keyboard(QUICK_REPLY_LABEL1,
                                            QUICK_REPLY_LABEL2,
                                            QUICK_REPLY_LABEL3),
            )
        else:
            # تحقق من إذا كان المستخدم مسجلاً من قبل
            already_referred = False
            try:
                with open("referrals.txt", "r", encoding="utf-8") as f:
                    for line in f:
                        if f"(ID: {uid})" in line:
                            already_referred = True
                            break
            except FileNotFoundError:
                pass

            if ref_code and not already_referred:
                username = message.from_user.username or message.from_user.first_name or "Unknown"
                try:
                    with open("referrals.txt", "a", encoding="utf-8") as f:
                        f.write(
                            f"المستخدم: {username} (ID: {uid}) تم دعوته من: {ref_code}\n"
                        )
                except Exception as e:
                    print(f"خطأ أثناء كتابة ملف الدعوات: {e}")
            elif ref_code and already_referred:
                print(f"المستخدم {uid} دخل برابط دعوة لكن مسجّل من قبل.")

            bot.send_message(message.chat.id, "👋 مرحباً بك! في Jooker Store.")

    @bot.message_handler(func=lambda m: True, content_types=['text'])
    def handle_user_message(message):
        uid = message.from_user.id
        text = (message.text or "").strip()
        text_lower = text.lower()

        if uid in [ADMIN_ID1, ADMIN_ID2, ADMIN_ID3, ADMIN_ID4]:
            return

        price = ("اقوي خصوووومات على عملات تيك توك🔥\n"
                 "🪙 <b>350</b>=<b>205</b>💸\n"
                 "🪙 <b>700</b>=<b>405</b>💸\n"
                 "🪙 <b>1000</b>=<b>575</b>💸\n"
                 "🪙 <b>1400</b>=<b>810</b>💸\n"
                 "🪙 <b>1700</b>=<b>985</b>💸\n"
                 "🪙 <b>2550</b>=<b>1475</b>💸\n"
                 "🪙 <b>3500</b>=<b>2025</b>💸\n"
                 "🪙 <b>5000</b>=<b>2895</b>💸\n"
                 "🪙 <b>7000</b>=<b>4045</b>💸\n"
                 "🪙 <b>10000</b>=<b>5745</b>💸\n"
                 "🪙 <b>14000</b>=<b>8040</b>💸\n"
                 "🪙 <b>25000</b>=<b>14350</b>💸\n"
                 "🪙 <b>50000</b>=<b>28400</b>💸\n\n"
                 "موجود شحن أي كميه بفضل الله 🤍")

        greetings = {
            "السلام عليكم": "🌷 وعليكم السلام ورحمة الله وبركاته",
            "سلام": "🌸 وعليكم السلام",
            "مرحبا": "👋 مرحباً بك، كيف يمكنني مساعدتك؟",
            "هلا": "😊 هلا بك، كيف أقدر أساعدك؟",
            "أهلاً": "🌟 أهلاً وسهلاً بك",
            "صباح الخير": "☀️ صباح النور، كيف أقدر أساعدك؟",
            "مساء الخير": "🌙 مساء النور، أهلاً وسهلاً",
            "هاي": "👋 هاي، كيف أقدر أساعدك؟",
            "باي": "نورتنا 🤍",
            "أسعار": price,
            "اسعار": price,
            "الاسعار": price,
            "الأسعار": price,
            "اسعار شحن": price,
            "اسعار الشحن": price,
            "أسعار ألشحن": price
        }

        for key, reply in greetings.items():
            if key in text_lower:
                bot.send_message(message.chat.id, reply, parse_mode="HTML")
                return

        current_admin = get_assignment(uid)
        uname = message.from_user.username or message.from_user.first_name or "Unknown"

        if current_admin:
            try:
                bot.send_message(
                    current_admin,
                    f"✉️ رسالة جديدة من @{uname} (ID: {uid}):\n\n{text}",
                    parse_mode="HTML",
                )
            except Exception as e:
                print("خطأ عند إرسال رسالة للأدمن المرتبط:", e)
            return

        key = f"{uid}_{message.message_id}"
        add_pending_entry(key, uid, uname, text)

        # رد تلقائي للمستخدم بعد تسجيل الرسالة
        bot.send_message(
            message.chat.id,
            "✅ تم إرسال رسالتك للإدارة، يرجى الانتظار حتى قبولها من أحد الأدمن."
        )

        keyboard = types.InlineKeyboardMarkup()
        accept_btn = types.InlineKeyboardButton("✅ قبول",
                                                callback_data=f"accept:{key}")
        keyboard.add(accept_btn)

        for admin_id in [ADMIN_ID1, ADMIN_ID2, ADMIN_ID3, ADMIN_ID4]:
            try:
                bot.send_message(
                    admin_id,
                    f"📩 مستخدم @{uname} بعت رسالة جديدة.\n\nID: {uid}\nمفتاح: {key}",
                    reply_markup=keyboard,
                )
            except Exception as e:
                print("خطأ في إرسال تنبيه للأدمن:", admin_id, e)

    @bot.message_handler(content_types=['photo'])
    def handle_user_photo(message):
        uid = message.from_user.id
        if uid in [ADMIN_ID1, ADMIN_ID2, ADMIN_ID3, ADMIN_ID4]:
            return

        uname = message.from_user.username or message.from_user.first_name or "Unknown"
        photo = message.photo[-1].file_id
        caption = message.caption or ""

        current_admin = get_assignment(uid)
        if current_admin:
            try:
                bot.send_photo(
                    current_admin,
                    photo,
                    caption=f"📷 من @{uname} (ID: {uid})\n\n{caption}")
            except Exception as e:
                print("خطأ إرسال صورة للأدمن المرتبط:", e)
            return

        key = f"{uid}_{message.message_id}"
        text_store = f"PHOTO:{photo}|||{caption}"
        add_pending_entry(key, uid, uname, text_store)

        keyboard = types.InlineKeyboardMarkup()
        accept_btn = types.InlineKeyboardButton("✅ قبول",
                                                callback_data=f"accept:{key}")
        keyboard.add(accept_btn)

        for admin_id in [ADMIN_ID1, ADMIN_ID2, ADMIN_ID3, ADMIN_ID4]:
            try:
                bot.send_message(
                    admin_id,
                    f"📩 مستخدم @{uname} بعت صورة جديدة.\n\n(صورة)\n\nID: {uid}\nمفتاح: {key}",
                    reply_markup=keyboard,
                )
            except Exception as e:
                print("خطأ في إرسال تنبيه للصورة للأدمن:", e)

        bot.send_message(
            message.chat.id,
            "✅ تم استلام الصورة وسيتم عرضها بعد قبول أحد الأدمن.")

    def get_admin_name(admin_id):
        if admin_id == ADMIN_ID1:
            return ADMIN_NAME1
        elif admin_id == ADMIN_ID2:
            return ADMIN_NAME2
        elif admin_id == ADMIN_ID3:
            return ADMIN_NAME3
        elif admin_id == ADMIN_ID4:
            return ADMIN_NAME4
        else:
            return "الأدمن"

    @bot.callback_query_handler(
        func=lambda call: call.data and call.data.startswith("accept:"))
    def accept_callback(call):
        admin_id = call.from_user.id
        if admin_id not in [ADMIN_ID1, ADMIN_ID2, ADMIN_ID3, ADMIN_ID4]:
            bot.answer_callback_query(call.id, "❌ غير مسموح بهذا الإجراء.")
            return

        key = call.data.split(":", 1)[1]
        entry = get_pending_entry(key)
        if not entry:
            bot.edit_message_text(
                "⚠️ هذه الرسالة لم تعد متاحة (تم قبولها أو حذفها).",
                chat_id=call.message.chat.id,
                message_id=call.message.message_id)
            return

        remove_pending_entry(key)

        user_id = entry["user_id"]
        username = entry["username"]
        text = entry["text"]

        add_assignment(user_id, admin_id)

        try:
            if text.startswith("PHOTO:"):
                parts = text.split("|||", 1)
                photo_part = parts[0]
                caption = parts[1] if len(parts) > 1 else ""
                file_id = photo_part.split("PHOTO:", 1)[1]
                bot.send_photo(
                    admin_id,
                    file_id,
                    caption=f"📷 من @{username} (ID: {user_id})\n\n{caption}")
                bot.send_message(
                    admin_id,
                    f"تم ربط المستخدم @{username} (ID: {user_id}).\nاستخدم الزر أو رد بــ {QUICK_REPLY_LABEL1} عند الانتهاء.",
                    reply_markup=admin_keyboard(QUICK_REPLY_LABEL1,
                                                QUICK_REPLY_LABEL2,
                                                QUICK_REPLY_LABEL3))
            else:
                bot.send_message(
                    admin_id,
                    f"✉️ رسالة من @{username} (ID: {user_id}):\n\n{text}",
                    reply_markup=admin_keyboard(QUICK_REPLY_LABEL1,
                                                QUICK_REPLY_LABEL2,
                                                QUICK_REPLY_LABEL3))
        except Exception as e:
            print("خطأ عند إرسال المحتوى للأدمن بعد القبول:", e)
            bot.edit_message_text("حدث خطأ أثناء إرسال الرسالة للأدمن.",
                                  chat_id=call.message.chat.id,
                                  message_id=call.message.message_id)
            return

        admin_name = get_admin_name(admin_id)
        try:
            bot.send_message(
                user_id,
                f"✅ تم قبول رسالتك من قبل <b><u>{admin_name}</u></b>.",
                parse_mode="HTML")
        except Exception as e:
            print(f"خطأ في إرسال رسالة القبول للمستخدم: {e}")
