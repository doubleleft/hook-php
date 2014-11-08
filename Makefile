APIGEN_PATH = ~/Downloads/apigen

docs:
	mkdir -p ../hook-php-docs
	rm -rf ../hook-php-docs/.git
	php -d memory_limit=512M ${APIGEN_PATH}/apigen.php --destination ../hook-php-docs --debug \
																--exclude */tests/* \
																--source ./src/
	git init ../hook-php-docs
	cd ../hook-php-docs && git remote add origin git@github.com:doubleleft/hook-php.git && git checkout -b gh-pages && git add .  && git commit -m "update public documentation" && git push origin gh-pages -f
	open ../hook-php-docs/index.html
