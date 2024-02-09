/* もし同名のデータベースがある場合にそれを削除 */
DROP DATABASE IF EXISTS posse;

/* データベースposseを作成 */
CREATE DATABASE posse;

/* データベースposseを使用 */
USE posse;

/* questionテーブルの作成 */
CREATE TABLE questions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    -- 最初の列'id'を作成。 primary key を付与する。新しい行が挿入されるごとに列の値が自動的に増加する */
    content VARCHAR(255) NOT NULL,
    -- 次の列'name'を作成。255文字のテキストデータを使用。null値を許さない */
    image VARCHAR(255) not null,
    -- 最後の列に'description'を作成。255文字のテキストデータを使用。null値を許す */
    supplement VARCHAR(255)
);

/* データを挿入 */
INSERT INTO questions (id, content, image, supplement) VALUES
('1', '日本のIT人材が2030年には最大どれくらい不足すると言われているでしょうか？','img-quiz01.png','経済産業省 2019年3月 － IT 人材需給に関する調査'),
('2', '既存業界のビジネスと、先進的なテクノロジーを結びつけて生まれた、新しいビジネスのことをなんと言うでしょう？','img-quiz02.png','なし'),
('3', 'IoTとは何の略でしょう？','img-quiz03.png','なし'),
('4', 'サイバー空間とフィジカル空間を高度に融合させたシステムにより、経済発展と社会的課題の解決を両立する、人間中心の社会のことをなんと言うでしょう？','img-quiz04.png','Society5.0 - 科学技術政策 - 内閣府'),
('5', 'イギリスのコンピューター科学者であるギャビン・ウッド氏が提唱した、ブロックチェーン技術を活用した「次世代分散型インターネット」のことをなんと言うでしょう？','img-quiz05.png','なし'),
('6', '先進テクノロジー活用企業と出遅れた企業の収益性の差はどれくらいあると言われているでしょうか？','img-quiz06.png','Accenture Technology Vision 2021');


/* choicesテーブルの作成 */
CREATE TABLE choices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    question_id int NOT NULL,
    name VARCHAR(255) not null,
    valid int not null
);

/* データを挿入  idは自動付与なのでいらない*/
INSERT INTO choices (question_id, name, valid) VALUES
(1, '約28万人', 0),
(1, '約79万人', 1),
(1, '約183万人', 0),
(2, 'INTECH', 0),
(2, 'BIZZTECH', 0),
(2, 'X-TECK', 1),
(3, 'Internet of things', 1),
(3, 'Integrate into Technology', 0),
(3, 'Information on Tool', 0),
(4, 'Society 5.0', 1),
(4, 'CyPhy', 0),
(4, 'SDGs', 0),
(5, 'Web3.0', 1),
(5, 'NFT', 0),
(5, 'メタバース', 0),
(6, '約2倍', 0),
(6, '約5倍', 1),
(6, '約11倍', 0);

-- userテーブルの作成
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

INSERT INTO users (name, email, password) VALUES
('REON','tekitounameado@gmail.com','tekitouda');
