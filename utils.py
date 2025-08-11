# utils.py
import os
import re
import requests
import urllib.parse

# إعدادات السيرفر
STORAGE_URL = "http://omg-mh-tools1.mygamesonline.org/storage.php"
SECRET_KEY = "OMGVIP"

PENDING_FILE = "pending_messages.txt"
ASSIGNMENTS_FILE = "assignments.txt"


def remote_request(action, filename, data=None):
    params = {
        "key": SECRET_KEY,
        "action": action,
        "filename": filename
    }
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
    line = f"{key}|{user_id}|{username}|||{safe_text}\n"
    remote_request("append", PENDING_FILE, line)


def remove_pending_entry(key: str):
    ensure_file(PENDING_FILE)
    lines = remote_request("read", PENDING_FILE).splitlines()
    new_lines = [l for l in lines if not l.startswith(f"{key}|||")]
    remote_request("write", PENDING_FILE, "\n".join(new_lines) + ("\n" if new_lines else ""))


def get_pending_entry(key: str):
    ensure_file(PENDING_FILE)
    for line in remote_request("read", PENDING_FILE).splitlines():
        if line.startswith(f"{key}|||"):
            parts = line.rstrip("\n").split("|||", 3)
            if len(parts) == 4:
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
    remote_request("write", ASSIGNMENTS_FILE, "\n".join(new_lines) + ("\n" if new_lines else ""))


def get_assignment(user_id: int):
    ensure_file(ASSIGNMENTS_FILE)
    for line in remote_request("read", ASSIGNMENTS_FILE).splitlines():
        if line.startswith(f"{user_id}:"):
            parts = line.strip().split(":", 1)
            if len(parts) == 2:
                try:
                    return int(parts[1])
                except:
                    return None
    return None


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
