import telebot
from telebot.types import Message
from config import BOT_TOKEN  # تأكد أنك أنشأت ملف config.py وفيه BOT_TOKEN

bot = telebot.TeleBot("1567532491:AAE4GadH8Ic2R-TFbpghI6DHX4sO59f3YU8")


@bot.message_handler(commands=['start'])
def send_user_info(message: Message):
    user = message.from_user
    info = f"""👤 معلوماتك:
🆔 ID: {user.id}
👨‍💼 الاسم: {user.first_name}
🔗 اسم المستخدم: @{user.username if user.username else 'لا يوجد'}
🌐 اللغة: {user.language_code if user.language_code else 'غير محددة'}"""
    bot.send_message(message.chat.id, info)


bot.polling()
