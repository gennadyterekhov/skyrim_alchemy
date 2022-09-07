<p><a href="https://elderscrolls.fandom.com/wiki/Alchemy_(Skyrim)" target="_blank"><img src="https://static.wikia.nocookie.net/elderscrolls/images/b/b9/SkillAlchemy.png/revision/latest?cb=20120513065550" width="400" alt="Skyrim alchemy Logo"></a></p>
<p>
<a href="https://opensource.org/licenses/MIT">
<img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
</a>
</p>

## Skyrim alchemy

This app wraps a database of skyrim alchemy effects and ingredients

### How to run

Set environment variables in `.env` file. There is an example file `.env.example`  

Start containers  
`./vendor/bin sail up`  
  
Then populate the database  
`php artisan db:seed`


App will be available at  <a href="https://localhost" alt="localhost">localhost</a>

## CSV sources
The database is imported from csv files, you can find them here   
https://github.com/gennadyterekhov/skyrim_alchemy/tree/main/public/csv  
  
If you want to update the database, send an issue.
## License

Licensed under the [MIT license](https://opensource.org/licenses/MIT).
