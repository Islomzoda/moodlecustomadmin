import telebot
import requests
import json
import time

TOKEN = "6406496260:AAFWjxqKkyyN5N2Hilxbil-O7yXkdBdqUIQ"
CHAT_IDS = ["-1001925452270", "-1002144677415", "-1002056611872", "-1002131730381", "-1002134641485", "-1002070555735", "-1001995332874", "-1002072859818"]  # ID ваших групп
API_URL = "https://smc.jonee.ru/api/moodle/create"  # URL вашего API

bot = telebot.TeleBot(TOKEN)

@bot.message_handler(commands=['login'])
def login(message):
    user_id = message.from_user.id
    for chat_id in CHAT_IDS:
        try:
            member = bot.get_chat_member(chat_id, user_id)
            if member.status not in ["left", "kicked"]:
                user_data = {
                    "user": member.user.to_dict(),  # Отправка данных пользователя
                    "chat_id": chat_id,
                    "tariff": bot.get_chat(chat_id).title
                }
                # print(user_data)
                response = requests.post(API_URL, json=user_data)
                if response.status_code == 200:
                    print(response.content)
                    login_info = response.json()

                    login_message = f"{message.from_user.first_name}, ваши данные для входа:\n\nСервер: https://kurs.alaboom.org \nЛогин: <code>{login_info[0]['login']}</code>\nПароль: <code>{login_info[0]['password']} </code>"
                    bot.reply_to(message, login_message, parse_mode="HTML")
                else:
                    bot.reply_to(message, "Произошла ошибка при обработке вашего запроса.")
                return
        except telebot.apihelper.ApiException:
            pass
    bot.reply_to(message, "Вы не из наших групп, пожалуйста, не пишите мне!")

@bot.message_handler(commands=['start'])
def start(message):
    bot.reply_to(message, 'Ассалому алейкум барои логин ва пароли худро гирифтан тугмаи /login пахш кунед!')


while True:
    try:
         bot.polling()
    except Exception as e:
        time.sleep(3)
        print(e)
