# pfsense-portailcaptif-freeRadius-smsforwarder
- Personnalisation des pages d'authentification du portail captif pfSense (welcome, logerror, logout)
- Utilisation du package freeRadius avec une base de donnée RADIUS externe (schema.sql)
- Paiement et création de compte (purchase, process, succes, error)
- Utilisation de SMS Forwarder avec un API pour la vérification des paiements (api, sms_log) (https://github.com/bogkonstantin/android_income_sms_gateway_webhook/)
- Vérification régulière des infos de session avec Cron (logcheck)

Les fichiers de traitement de paiement dans (/usr/local/www/) et les fichiers pour l'authentification sur l'interface web de pfsense (custom portal pages)
