# bax-music
Rick and Morty Exam

To run containers
docker compose -f docker-compose.yml up -d

To build
npm run dev

clear cache
php bin/console cache:clear

To get inside container
docker exec -it bax-music bash

To access the project
http://localhost:8000/search/characters/dimension