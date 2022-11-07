<h1>project structure</h1>

* [x] db structure
    - table: user
        * id: varchar(uuid)
        * name: varchar
        * document_id: varchar
        * person_type: enum
        * email: varchar
        * password: varchar

    - table: seller
        * id: varchar(uuid)
        * name: varchar
        * document_id: varchar
        * person_type: enum
        * email: varchar
        * password: varchar

    - table: wallet
        * id: varchar(uuid)
        * user_id: varchar(uuid)
        * balance: decimal

    - table: transactions
        * id: varchar(uuid)
        * description: varchar
        * payer_wallet_id: varchar(uuid)
        * payee_wallet_id: varchar(uuid)
        * amount: decimal

* [x] entity
    * [x] relationships
        - [x] user:
            - has one wallet
        - [x] seller:
            - has one wallet
        - [x] wallet:
            - belongs to user
            - has many transactions
        - [x] transaction:
            - has one payer
            - has one payee

* [] endpoints structure
    * [x] auth
        * [x] register 
        * [x] login 
        * [x] logout
    * [] transactions
        * [] list self transactions 
        * [] make transaction
    * [] wallet
        * [] get balance account
* [] tests
* [] documentation with swagger
* [] docker

obs:
- [x] use uuid for data security.
- [] use services e repository layers.
- [] use form requests.
- [] use api resources.
- [] use api resources routes.
- [] use policy/gate for authorization.
- [] use laravel notifications(email/sms) with queue for async.
- [] use fk in migrations.

<h2>modelagem banco de dados</h2>

