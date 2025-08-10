# utils.py
import os
import re

PENDING_FILE = "pending_messages.txt"
ASSIGNMENTS_FILE = "assignments.txt"


def ensure_file(path):
    if not os.path.exists(path):
        open(path, "w", encoding="utf-8").close()


def add_pending_entry(key: str, user_id: int, username: str, text: str):
    ensure_file(PENDING_FILE)
    safe_text = text.replace("\n", "\\n")
    line = f"{key}|||{user_id}|||{username}|||{safe_text}\n"
    with open(PENDING_FILE, "a", encoding="utf-8") as f:
        f.write(line)


def remove_pending_entry(key: str):
    ensure_file(PENDING_FILE)
    with open(PENDING_FILE, "r", encoding="utf-8") as f:
        lines = f.readlines()
    with open(PENDING_FILE, "w", encoding="utf-8") as f:
        for line in lines:
            if not line.startswith(f"{key}|||"):
                f.write(line)


def get_pending_entry(key: str):
    ensure_file(PENDING_FILE)
    with open(PENDING_FILE, "r", encoding="utf-8") as f:
        for line in f:
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
    with open(PENDING_FILE, "r", encoding="utf-8") as f:
        for line in f:
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
    with open(ASSIGNMENTS_FILE, "a", encoding="utf-8") as f:
        f.write(f"{user_id}:{admin_id}\n")


def remove_assignment(user_id: int):
    ensure_file(ASSIGNMENTS_FILE)
    with open(ASSIGNMENTS_FILE, "r", encoding="utf-8") as f:
        lines = f.readlines()
    with open(ASSIGNMENTS_FILE, "w", encoding="utf-8") as f:
        for line in lines:
            if not line.startswith(f"{user_id}:"):
                f.write(line)


def get_assignment(user_id: int):
    ensure_file(ASSIGNMENTS_FILE)
    with open(ASSIGNMENTS_FILE, "r", encoding="utf-8") as f:
        for line in f:
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
