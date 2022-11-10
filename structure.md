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
            - has one wallet(morph)
        - [x] seller:
            - has one wallet(morph)
        - [x] wallet:
            - belongs to user(morph)
            - has many transactions
        - [x] transaction:
            - has one payer
            - has one payee

* [x] endpoints structure
    * [x] auth
        * [x] register 
        * [x] login 
        * [x] logout
    * [x] transactions
        * [x] make transaction
* [x] tests
* [x] documentation with swagger
* [x] docker

obs:
- [x] use uuid for data security.
- [x] use services e repository layers.
- [x] use form requests.


