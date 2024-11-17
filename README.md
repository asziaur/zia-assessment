# Prerequisites 
- Docker Desktop 
- If windows OS then WSL installation require.

# Flow the following steps for runing the assessment.
1. Clone repository
    $ git clone https://github.com/asziaur/zia-assessment.git
2. Run the follwing command: 
    $ docker-compose up -d --build
    $ docker-compose exec php-service bash
    $ cd search
    $ composer install
3. Exit from php-service by using command
    $ exit
4. Change ownership as per the userID(999) by using command (999:999 - userID:groupID)
    $ sudo chown -R 999:999 mysql/
5. Run the follwing command:
    $ docker-compose exec php-service bash
    $ cd search
    $ php bin/console doctrine:migrations:migrate
    $ php bin/console app:insert-dummy-products 
        output - 15 dummy products have been inserted successfully. 

6. Go browser and open url
    - http://localhost:8081/search

# MySQL connection for connect any IDE/CLI:
    - host: 127.0.0.1
    - port: 4306
    - user: zia or root
    - password: secret
    - database: assessment