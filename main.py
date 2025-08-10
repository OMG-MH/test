import os
import telebot
from flask import Flask, request
from config import BOT_TOKEN
from handlers import register_handlers  # نفس اللي عندك

bot = telebot.TeleBot(BOT_TOKEN)
register_handlers(bot)  # تسجيل أوامر وحداتك

app = Flask(__name__)

@app.route(f"/{BOT_TOKEN}", methods=['POST'])
def webhook():
    json_str = request.get_data().decode('UTF-8')
    update = telebot.types.Update.de_json(json_str)
    bot.process_new_updates([update])
    return "OK", 200

@app.route("/", methods=['GET'])
def home():
    return "🤖 البوت يعمل الآن Webhook!", 200

if __name__ == "__main__":
    WEBHOOK_URL = f"https://{os.environ.get('RENDER_EXTERNAL_HOSTNAME')}/{BOT_TOKEN}"
    bot.remove_webhook()
    bot.set_webhook(url=WEBHOOK_URL)
    print(f"✅ Webhook set to: {WEBHOOK_URL}")

    app.run(host="0.0.0.0", port=int(os.environ.get("PORT", 10000)))

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
