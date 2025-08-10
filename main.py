import os
import telebot
from flask import Flask, request
from config import BOT_TOKEN
from handlers import register_handlers

bot = telebot.TeleBot(BOT_TOKEN)
register_handlers(bot)

app = Flask(name)

@app.route('/')
def index():
    return "Bot is running"

@app.route(f"/{BOT_TOKEN}", methods=['POST'])
def receive_update():
    json_str = request.get_data().decode('UTF-8')
    update = telebot.types.Update.de_json(json_str)
    bot.process_new_updates([update])
    return "OK", 200

if name == "main":
    port = int(os.environ.get("PORT", 5000))
    app.run(host="0.0.0.0", port=port)

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
