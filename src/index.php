    <?php
    require('dbconnect.php');  //dbconnect.phpファイルを読み込む

//以下データの整形(week22)    
    $questions = $dbh -> query("SELECT * FROM questions")->fetchAll(PDO::FETCH_ASSOC);  //テーブルquestionsを変数questionsに格納 データベースに接続し、SQL文でデータを取得し、連想配列の形で返している。
    $choices = $dbh->query("SELECT * FROM choices")->fetchAll(PDO::FETCH_ASSOC);   //テーブルchoicesを変数choicesに格納

    foreach($questions as $qKey => $question){   //foreach(配列 as キー変数 => 値変数)  配列内のそれぞれの要素についてループ処理
        $question["choices"] = [];               //連想配列questionに新しいキー"choices"を持つ要素を作成し、その値を空とする。
        foreach($choices as $cKey => $choice){   //配列choices に対してループ処理
            if($choice["question_id"] == $question["id"]){   //もし配列choiceのquestion_idキーと配列questionのidキーが等しければ、
                $question["choices"][] = $choice;       //配列questonのchoicesキーとその後([])に配列choiceを代入  
            }
        }
        $questions[$qKey] = $question;        //元の配列questionの該当する位置に加工した配列questionsを代入  $qKeyは、c言語のnみたいなもの
    };
    ?>
    
    <!DOCTYPE html>
    <html lang="ja">

    <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITクイズ | POSSE 初めてのWeb</title>
    <!-- スタイルシート読み込み -->
    <link rel="stylesheet" href="../assets/styles/common.css">
    <!-- Google Fonts読み込み -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&family=Plus+Jakarta+Sans:wght@400;700&display=swap"
        rel="stylesheet">
    <script src="./assets/scripts/quiz.js" defer></script>            
    <!-- ?quiz3よりquizのほうが読み込んだ方がいい -->
    </head>

    <body>
    <header id="js-header" class="l-header p-header">
        <div class="p-header__logo"><img src="../assets/img/logo.svg" alt="POSSE"></div>
        <button class="p-header__button" id="js-headerButton"></button>
        <div class="p-header__inner">
        <div class="p-header__official">
            <a href="https://line.me/R/ti/p/@651htnqp?from=page" target="_blank" rel="noopener noreferrer" class="p-header__official__link--line">
            <i class="u-icon__line"></i>
            <span class="">POSSE公式LINEを追加</span>
            <i class="u-icon__link"></i>
            </a>
            <a href="" class="p-header__official__link--website">POSSE 公式サイト<i class="u-icon__link"></i></a>
        </div>
        <ul class="p-header__sns p-sns">
            <li class="p-sns__item">
            <a href="https://twitter.com/posse_program" target="_blank" rel="noopener noreferrer" class="p-sns__item__link"
                aria-label="Twitter">
                <i class="u-icon__twitter"></i>
            </a>
            </li>
            <li class="p-sns__item">
            <a href="https://www.instagram.com/posse_programming/" target="_blank" rel="noopener noreferrer"
                class="p-sns__item__link" aria-label="instagram">
                <i class="u-icon__instagram"></i>
            </a>
            </li>
        </ul>
        </div>
    </header>
    <!-- /.l-header .p-header -->


    <main class="l-main">
        <section class="p-hero p-quiz-hero">
            <div class="l-container">
                <h1 class="p-hero__title">
                <span class="p-hero__title__label">POSSE課題</span>
                <span class="p-hero__title__inline">IT</span>
                </h1>
            </div>
        </section>
        <!-- /.p-hero .p-quiz-hero -->

        <div class="p-quiz-container l-container">
        
            <!-- 以下検索したデータを出力する処理 week23 -->
            <!-- 配列questionの要素個数だけループ -->
            <?php for ($i = 0; $i < count($questions); $i++) { ?> 
                <!-- ?data-quiz="i"でもいけるぞ -->
                <!-- data-* 属性はカスタムデータをHTML要素に追加するための方法です -->
                <section class="p-quiz-box js-quiz" data-quiz="0">  
                <div class="p-quiz-box__question">    
                    <h2 class="p-quiz-box__question__title">
                        <!-- Q1 Q2を出力 -->
                        <span class="p-quiz-box__label">Q<?= $i + 1 ?></span>
                        <!--連想配列questionのcontentキーを出力  -->
                        <span class="p-quiz-box__question__title__text"><?= $questions[$i]["content"]; ?></span>
                    </h2>
                    <figure class="p-quiz-box__question__image">
                        <!-- phpの写真挿入 相対パスの中に埋め込む -->
                        <img src="../assets/img/quiz/<?= $questions[$i]["image"]; ?>" alt="">   
                    </figure>
                </div>
                <div class="p-quiz-box__answer">
                    <span class="p-quiz-box__label p-quiz-box__label--accent">A</span>
                    <ul class="p-quiz-box__answer__list">
                        <?php for($j = 0; $j < count($choices)/count($questions); $j++){ ?>
                            <li class="p-quiz-box__answer__item">
                                <!-- data-correct属性にデータべースの配列をぶち込むことで、jsでデータベースを扱かえられる -->
                            <button class="p-quiz-box__answer__button js-answer" data-correct=<?= $choices[$j+3*$i]["valid"]; ?>>    
                                <!-- ループ処理頑張ってね -->
                                <?= $choices[$j+3*$i]["name"]; ?>
                                <i class="u-icon__arrow"></i>
                            </button>
                        </li>
                        <?php } ?> 
                    </ul>
                    <div class="p-quiz-box__answer__correct js-answerBox">
                        <p class="p-quiz-box__answer__correct__title js-answerTitle"></p>
                        <p class="p-quiz-box__answer__correct__content">
                            <span class="p-quiz-box__answer__correct__content__label">A</span>
                            <span class="js-answerText"></span>
                        </p>
                    </div>
                </div>
                <cite class="p-quiz-box__note">
                    <i class="u-icon__note"></i>
                    <?= $questions[$i]["supplement"]; ?>
                </cite>
                </section>
            <?php } ?>    

        <!-- ./p-quiz-box -->
        </div>
        <!-- /.l-container .p-quiz-container -->
    </main>

    <div class="p-line">
        <div class="l-container">
        <div class="p-line__body">
            <div class="p-line__body__inner">
            <h2 class="p-heading -light p-line__title"><i class="u-icon__line"></i>POSSE 公式LINE</h2>
            <div class="p-line__content">
                <p>公式LINEにてご質問を随時受け付けております。<br>詳細やPOSSE最新情報につきましては、公式LINEにてお知らせ致しますので<br>下記ボタンより友達追加をお願いします！</p>
            </div>
            <div class="p-line__footer">
                <a href="https://line.me/R/ti/p/@651htnqp?from=page" target="_blank" rel="noopener noreferrer"
                class="p-line__button">LINE追加<i class="u-icon__link"></i></a>
            </div>
            </div>
        </div>
        </div>
    </div>
    <!-- /.p-line -->

    <footer class="l-footer p-footer">
        <div class="p-fixedLine">
        <a href="https://line.me/R/ti/p/@651htnqp?from=page" target="_blank" rel="noopener noreferrer"
            class="p-fixedLine__link">
            <i class="u-icon__line"></i>
            <p class="p-fixedLine__link__text"><span class="u-sp-hidden">POSSE</span>公式LINEで<br>最新情報をGET！</p>
            <i class="u-icon__link"></i>
        </a>
        </div>
        <div class="l-footer__inner">
        <div class="p-footer__siteinfo">
            <span class="p-footer__logo">
            <img src="../assets/img/logo.svg" alt="POSSE">
            </span>
            <a href="https://posse-ap.com/" target="_blank" rel="noopener noreferrer"
            class="p-footer__siteinfo__link">POSSE公式サイト</a>
        </div>
        <div class="p-footer__sns">
            <ul class="p-sns__list p-footer__sns__list">
            <li class="p-sns__item">
                <a href="https://twitter.com/posse_program" target="_blank" rel="noopener noreferrer"
                class="p-sns__item__link" aria-label="Twitter">
                <i class="u-icon__twitter"></i>
                </a>
            </li>
            <li class="p-sns__item">
                <a href="https://www.instagram.com/posse_programming/" target="_blank" rel="noopener noreferrer"
                class="p-sns__item__link" aria-label="instagram">
                <i class="u-icon__instagram"></i>
                </a>
            </li>
            </ul>
        </div>
        </div>
        <div class="p-footer__copyright">
        <small lang="en">©︎2022 POSSE</small>
        </div>
    </footer>
    <!-- /.l-footer .p-footer -->

    </body>

    </html>
