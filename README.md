**サービスの概要**   
現在、多くの企業が直面している課題の一つに、離職率の低下が挙げられます。この問題の背後には、管理職が部下の抱える課題に十分な時間とリソースを割けず、結果として部下が抱える課題の解決が後回しになっていることが原因だと考えています。  
そのため私たちのプロダクトは、企業の課題を効率的かつ確実に解決するため、視覚的なデータ表示、AI予測、タスク管理システムを活用した多角的アプローチでサポートします。  

**アプリケーションのイメージ**  
 - 企業情報/管理者情報登録  
事前に企業や管理者が情報を登録しておくことで、原因を予想する時や施策の案を提示する時に、より角度の高い情報を提供することができる。 
<table>
      <tr>
        <td align="center">
          <img src="./images/company.png" alt="企業登録" width="100%">
        </td>
        <td align="center">
          <img src="./images/admin.png" alt="管理者登録" width="100%">
        </td>
      </tr>
    </table>  

 - ホーム画面  
 全体スコア・スコアの高低と前回比の大小に基づいたスコアマップ・項目ごとのスコアと前回比・AIによる原因予想を見ることができる。散布図形式でのグラフやAIでの情報の提示などを用いて、多くの情報を提示する中でも管理者が適切な情報を選べるようにした。  
<table>
  <tr>
    <td align="center">
      <img src="./images/top_home.png" alt="スコア表示" width="100%">
    </td>
    <td align="center">
      <img src="./images/bottom_home.png" alt="因果推論" width="100%">
    </td>
  </tr>
</table>  

 - 施策立案画面  
 選択した項目・原因に対して、事前に登録した情報やアンケートのデータをもとに適切であると考えられる施策をAIが提案する。また、チャット機能で管理者自身の部署に応じた施策になるように変更を加え、概要を記入して保存することができる。  
 ![施策立案](./images/measure_create.png)  

 - 施策詳細画面  
 施策の概要の確認とタスク管理をすることができる。タスクカードの移動などで視覚的にもタスクの管理がしやすい他、設定したタスクの期限に合わせてリマインド機能も搭載し、管理者が施策を実行しやすいようにサポートする。  
<table>
      <tr>
        <td align="center">
          <img src="./images/measure_index.png" alt="施策一覧" width="100%">
        </td>
        <td align="center">
          <img src="./images/measure_show.png" alt="施策詳細" width="100%">
        </td>
      </tr>
    </table>  

 - アンケート回答画面  
 アンケートの対象者である従業員が本音で、積極的に回答できるように、アンケートの5択での実施のほか、導入画面・設問のテキストの工夫、プログレスバーの設置などを施している。  
<table>
      <tr>
        <td align="center">
          <img src="./images/survey.png" alt="サーベイ" width="100%">
        </td>
        <td align="center">
          <img src="./images/survey_select.png" alt="組織選択" width="100%">
        </td>
      </tr>
    </table>


 **環境構築**  
 注意...**macOS 専用アプリケーション**  
 このソフトウェアは現在 macOS 環境のみに対応しています。他のオペレーティングシステム（Windows, Linux等）では動作しませんのでご注意ください。  


Laravel インストール方法  
docker compose up -d (コンテナをたてる)  
docker compose exec app sh (appコンテナに入る)  

env.ファイルの追加と書き換え  
.env.exampleをコピーして複製  
cp .env.example .env  
src > .env の内容を以下のように書き換える    
 DB_CONNECTION=mysql  
 DB_HOST=mysql  
 DB_PORT=3306  
 DB_DATABASE=website  
 DB_USERNAME=posse  
 DB_PASSWORD=password  
 

 MAIL_MAILER=smtp  
 MAIL_HOST=mailpit  
 MAIL_PORT=1025  
 MAIL_USERNAME=null  
 MAIL_PASSWORD=null  
 MAIL_ENCRYPTION=null  
 MAIL_FROM_ADDRESS="coret@example.com"  
 MAIL_FROM_NAME="Coret"  
 
以下のコードを.envファイルの一番下に追記する  
・注意  
OPENAI_API_KEYを指定してください。publicリポジトリにはchatgptのAPIkeyを指定できないため、あなたのkeyを設定する必要があります。  
OPENAI_API_KEY="あなたのopenapikeyを指定"  
ターミナルで以下のコマンドを実行する  
composer install  
php artisan key:generate  
composer require guzzlehttp/guzzle   


データベースの作成  
ターミナルで以下のコマンドを実行する  
php artisan migrate --seed  

フロントを見るための環境の構築  
exitして以下のコマンドを実行する  
docker compose exec node sh  
npm install  
npm run dev  


**サイトの操作手順**  
管理者が使用する画面  
URL http://localhost/login  
にアクセスし、ログインする。ログイン情報は下記。  
 - メールアドレス test@example.com  
 - パスワード password  


ホーム画面  
アンケート項目一覧の中から1つ選択する→画面右側に項目の詳細（原因と過去の施策）が表示される  
「施策の立案」ボタンをクリック→施策立案画面に遷移  
施策立案画面  
施策に対してチャット部分（画面左側）でコメントを送信し、施策を修正する  
施策が定まったら画面右側の記入欄に記入し、「実行」ボタンをクリック  
立案完了画面  
「施策一覧画面へ」ボタンをクリック→施策一覧画面へ遷移  
施策一覧画面  
実施中のステータスのついた施策の施策名をクリック→その施策の詳細画面へ遷移  
施策詳細画面  
タスク一覧の未完了部分にあるタスクカードのチェックボタンをクリック→タスクカードが完了部分へ移動する  


企業の情報を登録する画面  
URL http://localhost/company/register/step1  
にアクセス  
 以下の企業情報を登録する  
会社名/従業員数/事業年数/昇給・評価の頻度/給与体系の透明性/評価制度の種類/組織構造（部署・課・係など）  
   ➡登録が完了したら企業固有の「企業コード（4桁の数字）」が発行される  
「管理者登録ページへ」ボタンをクリックする→管理者登録画面へ遷移  
管理者登録画面  
 以下の情報を登録する  
先ほど発行された企業コード  
管理者の氏名/メールアドレス/パスワード/役職/企業情報入力時に登録した階層に沿った自身の所属する組織  
業界/組織の人数/1on1の実施頻度/年齢層/リモートワークの可否/フレックスタイムの有無/平均残業時間  
「ログインへ」ボタンをクリックする→ログイン画面へ遷移  
ログイン画面  
 入力して「ログイン」ボタンをクリック→ホーム画面に遷移  

アンケート回答画面  
URL http://localhost/survey/1  
にアクセス  
「はじめる」ボタンをクリック→情報を入力し「回答画面へ」をクリック→アンケート回答画面へ遷移  
→アンケート16問に回答して「送信」ボタンをクリック→アンケート完了画面へ遷移  