from telegram import Update
from telegram.ext import Application, CommandHandler, MessageHandler, filters
from telegram.ext import ContextTypes
import re

# تعريف التوكن الخاص بالبوت
API_TOKEN = '1567532491:AAE4GadH8Ic2R-TFbpghI6DHX4sO59f3YU8'


# دالة بدء التفاعل مع البوت
async def start(update: Update, context: ContextTypes.DEFAULT_TYPE):
    await update.message.reply_text("مرحباً! أرسل لي صورة وسأقوم بتحويلها.")


# دالة معالجة الصور فقط
async def handle_photo(update: Update, context: ContextTypes.DEFAULT_TYPE):
    user_id = update.message.from_user.id
    user_name = update.message.from_user.username or update.message.from_user.first_name or "Unknown"

    # الحصول على الملف ID لأعلى دقة صورة
    photo_id = update.message.photo[-1].file_id
    file = await context.bot.get_file(photo_id)
    file_url = file.file_path  # الرابط المباشر للصورة

    # إرسال الرد للمستخدم
    await update.message.reply_text(
        f"تم استلام الصورة من @{user_name} (ID: {user_id}).")

    # إرسال رابط الصورة للأدمنين (أو يمكنك إرسال الصورة نفسها إذا أردت)
    await context.bot.send_message(
        712462503, f"صورة جديدة من @{user_name} (ID: {user_id}):\n{file_url}")


# دالة رئيسية لبدء البوت
def main():
    application = Application.builder().token(API_TOKEN).build()

    # إضافة معالج بدء البوت
    application.add_handler(CommandHandler("start", start))

    # إضافة معالج للصور فقط
    application.add_handler(MessageHandler(filters.PHOTO, handle_photo))

    # بدء البوت
    application.run_polling()


if __name__ == '__main__':
    main()
