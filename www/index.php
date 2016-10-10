<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Добро пожаловать");
?>
<div><img src="logo-abris.png" /></div>
<p>Добро пожаловать на заготовку сайта мило созданной для вас <a href="http://meet.bitrix.expert">сообществом Битрикс-Экспертов</a>!</p>
<p><a href="/bitrix/admin/">Перейти в админку сайта</a></p>

<h2>Как этим пользоваться?</h2>
<p>Первый vagrant up создаёт машину и активизирует её. По завершению работы нужно ввести vagrant halt, что "легально" выключит машину. При повторном vagrant up машина включится с уже инициализированного состояния, гораздо быстрее.<br/>
Читайте доку.</p>

<h2>Зачем это нужно?</h2>
<p>Не смотря на то, что виртуальная машина создаётся долго, это происходит только при первой инициализации. Преимуществ же вы получаете море!</p>
    <ul>
        <li>Вы получаете среду, основанную на рекомендациях вендора, ведь в качестве окружения используется BitrixEnv!</li>
        <li>Исходники Битрикса также берутся самые новые, с официального сайта. Установка максимально приближена к стандартной.</li>
        <li>Разработка может вестись неограниченно долго. Кончилась демо-лицензия? Удалите виртуальную машину и поднимите заново!</li>
        <li>У вас "из коробки" работают миграции и консольные команды, основанные на решении console jedi</li>
        <li>"Из коробки" работает composer, с помощью которого можно подключать другие опенсорс-модули для 1С-Битрикс</li>
        <li>Вы можете использовать это решение с Docker, VirtualBox, VMWare и другими платформами <a href="https://www.vagrantup.com/docs/providers/">благодаря vagrant</a></li>
        <li>Не забывайте про больше возможностей vagrant, например, <a href="https://www.vagrantup.com/docs/share/">sharing</a></li>
        <li>В composer.json также прописаны ускоряющие разработку решения <a href="http://bbc.bitrix.expert">bbc</a> и <a href="#">adminhelper</a>, которые ускоряют разработку по-настоящему используя возможности ООП.</li>
    </ul>

<h2>Как это использовать</h2>
<h3>PHP Storm / другая IDE</h3>
<p>Так как мы заранее пробросили порты - Вы можете подключиться к базе Битрикса из вашей IDE!</p>
<p>Порт, на который пробрасыватеся MySQL база на хост машину, прописан в vagrant/Vagrantfile. По умолчанию это 8889.</p>

<h3>Папка bitrix и git</h3>
<p>Крайне рекомендовано положить исходники вашего Битрикса под git, в отдельный репозиторий, чтобы версия на продуктиве и на вашей виртуальной машине была однозначно одинаковой!</p>
<p>Подробнее причины подобного действа рассматриваются <a href="https://www.youtube.com/watch?v=Q26FertI_wo">видеоуроке</a></p>

<h3>Composer и проекты на Битрикс</h3>
<p>@notice: не забудьте закоммитить ваш composer.lock! Он должен лежать в репозитории!</p>

<h2>Благодарности</h2>
<p><a href="https://github.com/nook-ru">Марат</a> и <a href="http://may-cat.ru/">Игорь</a> из <a href="http://meet.bitrix.expert">Битрикс-Экспертов</a></p>

<h2>Что почитать для улучшения навыков</h2>
    <ul>
        <li><a href="https://www.vagrantup.com/docs/">Документацию по Vagrant</a></li>
        <li>
            <a href="https://www.google.ru/webhp?sourceid=chrome-instant&ion=1&espv=2&ie=UTF-8#q=bash%20%D1%83%D1%87%D0%B5%D0%B1%D0%BD%D0%B8%D0%BA">Учебники
                по bash</a>
        </li>
        <li>
            Статьи о Композере
            <a href="http://samokhvalov.info/blog/all/composer-installers-1-0-24/">раз</a>
            <a href="http://zhurov.me/blog/bitrix-loves-composer.html">два</a> <a href="http://bitrix.expert/tekhnologii/bitriks-composer-marketpleys-i-vse-vse-vse/">три</a>
        </li>
        <li><a href="https://www.youtube.com/watch?v=WcRic8ONJXM&list=PLpqIEtc3sTJmVj0qVr37IWVbnGVOMSCKd">видеоуроки на ютубе</a></li>
        <li><a href="http://samokhvalov.info/blog/all/openconf-2016/">Console Jedi</a></li>
        <li><a href="http://samokhvalov.info/blog/all/bitrix-admin-helper/">Admin Helper</a></li>
        <li><a href="https://www.youtube.com/playlist?list=PLpqIEtc3sTJkb5pSSMd0rX_uaeeKQ1dnO">Материалы с Openconf</a></li>
    </ul>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>