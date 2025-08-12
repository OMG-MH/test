# utils.py
import os
import re
import requests
import urllib.parse
import threading
import time

# إعدادات السيرفر
STORAGE_URL = "http://omg-mh-tools1.mygamesonline.org/storage.php"
SECRET_KEY = "OMGVIP"

PENDING_FILE = "pending_messages.txt"
ASSIGNMENTS_FILE = "assignments.txt"


def remote_request(action, filename, data=None):
    params = {"key": SECRET_KEY, "action": action, "filename": filename}
    if data is not None:
        params["data"] = data
    response = requests.get(STORAGE_URL, params=params)
    response.raise_for_status()
    return response.text


def ensure_file(path):
    # لو الملف مش موجود على السيرفر، نعمله إنشاء فارغ
    if remote_request("read", path) == "":
        remote_request("write", path, "")


def add_pending_entry(key: str, user_id: int, username: str, text: str):
    ensure_file(PENDING_FILE)
    safe_text = text.replace("\n", "\\n")
    line = f"{key}|||{user_id}|||{username}|||{safe_text}\n"
    remote_request("append", PENDING_FILE, line)


def remove_pending_entry(key: str):
    ensure_file(PENDING_FILE)
    lines = remote_request("read", PENDING_FILE).splitlines()
    new_lines = [l for l in lines if not l.startswith(f"{key}|||")]
    remote_request("write", PENDING_FILE,
                   "\n".join(new_lines) + ("\n" if new_lines else ""))


def get_pending_entry(key: str):
    ensure_file(PENDING_FILE)
    for line in remote_request("read", PENDING_FILE).splitlines():
        if line.startswith(f"{key}|||"):
            print(f"test1:{line}")
            parts = line.rstrip("\n").split("|||", 3)
            print(f"test2:{line}")
            if len(parts) == 4:
                print(f"test3:{line}")
                return {
                    "key": parts[0],
                    "user_id": int(parts[1]),
                    "username": parts[2],
                    "text": parts[3].replace("\\n", "\n"),
                }
    return None


def list_all_pending():
    ensure_file(PENDING_FILE)
    results = []
    for line in remote_request("read", PENDING_FILE).splitlines():
        parts = line.rstrip("\n").split("|||", 3)
        if len(parts) == 4:
            results.append({
                "key": parts[0],
                "user_id": int(parts[1]),
                "username": parts[2],
                "text": parts[3].replace("\\n", "\n"),
            })
    return results


def add_assignment(user_id: int, admin_id: int):
    ensure_file(ASSIGNMENTS_FILE)
    remove_assignment(user_id)
    remote_request("append", ASSIGNMENTS_FILE, f"{user_id}:{admin_id}\n")


def remove_assignment(user_id: int):
    ensure_file(ASSIGNMENTS_FILE)
    lines = remote_request("read", ASSIGNMENTS_FILE).splitlines()
    new_lines = [l for l in lines if not l.startswith(f"{user_id}:")]
    remote_request("write", ASSIGNMENTS_FILE,
                   "\n".join(new_lines) + ("\n" if new_lines else ""))


def get_assignment(user_id: int):
    ensure_file(ASSIGNMENTS_FILE)
    try:
        content = remote_request("read", ASSIGNMENTS_FILE)
        print(content)
        if not isinstance(content, str):
            return None
        for line in content.splitlines():
            line = line.strip()
            if not line:
                continue  # تجاهل السطور الفارغة
            if line.startswith(f"{user_id}:"):
                parts = line.split(":", 1)
                if len(parts) == 2 and parts[1].strip().isdigit():
                    return int(parts[1])
    except Exception as e:
        print(f"خطأ في get_assignment: {e}")
    return None


def send_countdown_message(bot, chat_id, message_text, seconds=5):
    try:
        # أرسل الرسالة مع المؤقت الأولي
        countdown_msg = bot.send_message(chat_id,
                                         f"{message_text} ({seconds})")

        # دالة داخلية للتحديث والحذف
        def countdown_and_delete(msg_id, chat_id):
            for i in range(seconds, 0, -1):
                try:
                    bot.edit_message_text(f"{message_text} ({i})", chat_id,
                                          msg_id)
                except:
                    pass
                time.sleep(1)
            try:
                bot.delete_message(chat_id, msg_id)
            except:
                pass

        # شغّل المؤقت في Thread منفصل حتى لا يوقف البوت
        threading.Thread(target=countdown_and_delete,
                         args=(countdown_msg.message_id,
                               countdown_msg.chat.id)).start()

    except Exception as e:
        bot.send_message(chat_id, f"❌ خطأ عند إرسال العد التنازلي: {e}")


def edit_with_countdown(bot,
                        chat_id,
                        message_id,
                        base_text,
                        seconds=5,
                        final_text=None,
                        delete_after=False):

    def countdown_and_edit():
        for i in range(seconds, 0, -1):
            try:
                bot.edit_message_text(f"{base_text} ({i})", chat_id,
                                      message_id)
            except:
                pass
            time.sleep(1)

        try:
            if delete_after:
                bot.delete_message(chat_id, message_id)
            elif final_text:
                bot.edit_message_text(final_text, chat_id, message_id)
        except:
            pass

    threading.Thread(target=countdown_and_edit).start()


def extract_user_id(text: str):
    match = re.search(r"ID:\s*(\d+)", text)
    return int(match.group(1)) if match else None


def admin_keyboard(QUICK_REPLY_LABEL1, QUICK_REPLY_LABEL2, QUICK_REPLY_LABEL3):
    from telebot import types
    markup = types.ReplyKeyboardMarkup(resize_keyboard=True)
    markup.row(
        types.KeyboardButton(QUICK_REPLY_LABEL2),
        types.KeyboardButton(QUICK_REPLY_LABEL3),
        types.KeyboardButton(QUICK_REPLY_LABEL1),
    )
    return markup
