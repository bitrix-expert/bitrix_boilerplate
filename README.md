# Запуск машины

Машина снабжена BitrixVM и запускется простой командой
`vagrant up`
Выполните её в папке `vagrant/` и наслаждайтесь, как машина настраивается сама если вы сделаете несколько вещей, описанных ниже.

Виртуальная машина при первом старте "поднимается" достаточно долго и потребует порядочно много траффика. Но на выходе Вы получите идеально подходящую для разработки среду.

После настройки сайт будет доступен по адресу 
`http://127.0.0.1:8888`

На первой же странице вас встретят дополнительные инструкции и фишки.

## Подготовка к запуску машины

В папке `/vagrant.custom/` нужно положить несколько файлов, характерных именно для вашего окружения.

```
/vagrant.custom/ssh/id_rsa
/vagrant.custom/license.php
```

Пояснения по поводу этих файлов можно прочитать в `/vagrant.custom/README.md`
