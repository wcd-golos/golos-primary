# Обзор

MVP для интеграции Golos.io - проект [Market](https://golos.wecandevelopit.com/)

## Основная идея

Интеграция платформы Golos.io в качестве модуля поддержки комментирования товаров в Интернет-магазине. 
На странице деталей товара пользователи оставляют отзывы, отмечая свои впечатления о потреблении (использовании) данного товара, создава уникальный контент. Эти отзывы регистируются на платформе Golos.io как посты и поддерживаются общей инфраструктурой блокчейна.
Предполагается, что комментарии могут оставлять пользователи, зарегистрированные в golos.io.

## Функционал

Проект использует API Golos.io для реализации функционала создания, просмотра и редактирования комментариев на странице деталей товара.
Функционал взаимодействия с платформой golos.io включает в себя:
- создание нового отзыва (поста golos);
- получение с golos всех постов, связанных с данным товаром (созданных ранее пользователями);
- получение всех комментариев конкретного поста с golos;
- добавление нового комментария к посту golos;
- апвоут поста и комментария к посту.

Все комментарии, добавленные к постам на самой платформе golos.io, также отображаются в комментариях на странице деталей товара.
