import telebot
import requests
import json
import time

TOKEN = "6406496260:AAGeZS22r6fcfI2hQoNJ6_0fzLKCg5ARXd4"
CHAT_IDS = ["-1001925452270", "-1002144677415", "-1002056611872", "-1002131730381", "-1002134641485",  "-1001995332874", "-1002072859818"]  # ID ваших групп
MPSTATS_CHAT_IDS = ["-1001925452270", "-1002144677415", "-1002056611872", "-1002131730381", "-1002134641485", "-1001995332874", "-1002072859818", "-1002119843721"]  # ID ваших групп
API_URL = "https://smc.jonee.ru//api/"  # URL вашего API

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
                response = requests.post(f"{API_URL}moodle/create", json=user_data)
                print(response.status_code)
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


@bot.message_handler(commands=['mpstats'])
def login(message):
    user_id = message.from_user.id
    for chat_id in MPSTATS_CHAT_IDS:
        try:
            member = bot.get_chat_member(chat_id, user_id)
            if member.status not in ["left", "kicked"]:
                user_data = {
                    "user": member.user.to_dict(),  # Отправка данных пользователя
                    "chat_id": chat_id,
                }
                # print(user_data)
                
                URL = f"{API_URL}mpstats/access"
                print(URL)
                response = requests.post(URL, json=user_data)
                print(response.status_code)
                if response.status_code == 200:
                    print(response.content)
                    mpstats_info = response.json()
                    mpstats_message = f"Клиент: @menej_tj\nid {mpstats_info['mpstats_id']}\n\n👤 Логин: <code>{mpstats_info['login']}</code>\n🔑 Пароль: <code>{mpstats_info['password']}</code>\n\n✨ API Key: <code>{mpstats_info['api_key']}</code>\n\n🔗 Ссылка для входа: http://mphero.io/login\nСрок действия до: {mpstats_info['expire_at']}"
                    bot.reply_to(message, mpstats_message, parse_mode="HTML")
                else:
                    bot.reply_to(message, "Произошла ошибка при обработке вашего запроса.")
                return
        except telebot.apihelper.ApiException:
            pass
    bot.reply_to(message, "Вы не из наших групп, пожалуйста, не пишите мне!")

@bot.message_handler(commands=['start'])
def start(message):
    bot.reply_to(message, 'Ассалому алейкум барои логин ва пароли худро гирифтан тугмаи /login пахш кунед!')

@bot.message_handler(func=lambda message: message.chat.type == 'private')
def any_massages(message):
    bot.reply_to(message, 'Бубахшед барои хали масала ба @menej_tj мурочиат кунед')


while True:
    try:
         bot.polling()
    except Exception as e:
        time.sleep(3)
        print(e)
