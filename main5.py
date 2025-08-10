import telebot
from telebot.types import InlineKeyboardMarkup, InlineKeyboardButton

TOKEN = "1567532491:AAE4GadH8Ic2R-TFbpghI6DHX4sO59f3YU8"
bot = telebot.TeleBot(TOKEN)

@bot.message_handler(commands=['start'])
def start(message):
    markup = InlineKeyboardMarkup()
    markup.add(InlineKeyboardButton("✅ قبول", callback_data="accept"))
    bot.send_message(message.chat.id, "اضغط زر القبول:", reply_markup=markup)

@bot.callback_query_handler(func=lambda call: call.data == "accept")
def callback_accept(call):
    print(f"Callback received: {call.data} from user {call.from_user.id}")
    bot.answer_callback_query(call.id, "تم الضغط على قبول!")
    bot.edit_message_text("تم قبول الرسالة ✅",
                          chat_id=call.message.chat.id,
                          message_id=call.message.message_id,
                          reply_markup=None)

print("✅ البوت شغال الآن...")
bot.polling()
