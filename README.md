<p><a href="https://elderscrolls.fandom.com/wiki/Alchemy_(Skyrim)" target="_blank"><img src="https://static.wikia.nocookie.net/elderscrolls/images/b/b9/SkillAlchemy.png/revision/latest?cb=20120513065550" width="400" alt="Skyrim alchemy Logo"></a></p>
<p>
<a href="https://opensource.org/licenses/MIT">
<img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
</a>
</p>

# Skyrim alchemy

This app wraps a database of Skyrim alchemy effects and ingredients.  

Export files also contain data for Oblivion and Morrowind, but the web app only works for Skyrim.  
For the app for all games, see [this](https://the-elder-scrolls-alchemy.website.yandexcloud.net/#/)

## How to run

Set environment variables in `.env` file. There is an example file `.env.example`  

Start containers  
`./vendor/bin sail up`  

Enter php container  
`docker-compose exec php-laravel bash`

Inside the container:

Run migrations  
`php artisan migrate`

Then populate the database  
`php artisan db:seed`


App will be available at  <a href="https://localhost" alt="localhost">localhost</a>

## Export

### Skyrim

[json](https://github.com/gennadyterekhov/skyrim_alchemy/tree/main/public/export-files/skyrim/skyrim.json)

The database is imported from csv files, you can find them here   
[effects csv](https://github.com/gennadyterekhov/skyrim_alchemy/tree/main/public/export-files/skyrim/effects.csv)  
[ingredients csv](https://github.com/gennadyterekhov/skyrim_alchemy/tree/main/public/export-files/skyrim/ingredients.csv)

### Oblivion

[json](https://github.com/gennadyterekhov/skyrim_alchemy/tree/main/public/export-files/oblivion/oblivion.json)  

### Morrowind

[json](https://github.com/gennadyterekhov/skyrim_alchemy/tree/main/public/export-files/morrowind/morrowind.json)  

### Credits

credit for the data goes to [hogart](https://github.com/hogart/alchemy)  
and to all the people who contributed to [fandom](https://elderscrolls.fandom.com/wiki/Ingredients_(Morrowind)) and to [UESP](https://en.uesp.net/wiki/Morrowind:Ingredients)  

## License

Licensed under the [MIT license](https://opensource.org/licenses/MIT).
