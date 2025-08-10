# main.py
import telebot
from config import BOT_TOKEN
from handlers import register_handlers

bot = telebot.TeleBot(BOT_TOKEN)
register_handlers(bot)

print("✅ البوت يعمل الآن ...")
bot.infinity_polling()

# main.py
# from telegram.ext import Application
# from config import BOT_TOKEN
# from handlers import register_handlers

# def main():
#   app = Application.builder().token(BOT_TOKEN).build()
#   register_handlers(app)
#   print("✅ البوت يعمل الآن ...")
#   app.run_polling()

# if __name__ == '__main__':
#   main()
