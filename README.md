# Please follow the instructions

`Server Requirements:` <br>
`PHP: `Php server & CLI version >= 7.4 <br>
`MySQL: `MySQL version >= 8.0 <br>


`step 1:` clone this git repository <br>
`step 2:` copy .env.example to .env <br>
`step 3:` Set <b>WEATHER_BASE_URL</b> & <b>WEATHER_APP_ID</b> in .env <br>
`step 4:` Configure mail info in .env for forgot password <br>
`step 5:` run command <code>composer install</code> <br>
`step 6:` run command <code>npm install & npm run dev</code> <br>
`step 7:` run command <code>php artisan migrate</code><br>
`step 8:` run command <code>php artisan db:seed</code> <br>
`step 9:` run command <code>php artisan key:generate</code> <br>
`step 10:` run command <code>php artisan schedule:work</code> to fetch weather data in every 10 minutes<br>

### Regards [Md Riaz Hossain Fahad](https://github.com/RiazHossainFahad)