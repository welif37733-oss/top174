TOP174 — ПАКЕТ ДЛЯ BEGET (public_html)

Сборка: 2026-02-20

КАК РАЗВЕРНУТЬ "С НУЛЯ" НА BEGET:
1) Панель Beget → Файловый менеджер → сайт top174.ru → public_html.
2) Удалите всё содержимое public_html (или перенесите в backup-папку).
3) Загрузите архив и РАСПАКУЙТЕ так, чтобы файлы лежали прямо в public_html:
   index.html, styles.css, script.js, robots.txt, sitemap.xml, 404.html, папки assets/, api/, metallokonstrukcii/ и т.д.
   Важно: НЕ должно быть public_html/top174_public_html/index.html.
4) Проверьте:
   - http://top174.ru/ (главная)
   - http://top174.ru/robots.txt
   - http://top174.ru/sitemap.xml
   - http://top174.ru/api/health.php (должно быть "ok")
5) SSL:
   - включите Let’s Encrypt
   - после включения раскомментируйте редирект на HTTPS в .htaccess
6) Telegram-форма:
   - api/send.php: вставьте BOT_TOKEN
   - CHAT_ID сейчас: 547370288 (личка). Если нужен канал/группа — замените на -100...
   - в Telegram обязательно нажмите /start у бота (если отправка в личку)

