#Run with sudo 

#write out current crontab
crontab -l > mycron
#echo new cron into cron file
echo "0 0 * * * cd /var/www/html/Budgetary/cron; php -q update.php" >> mycron
#install new cron file
crontab mycron
rm mycron