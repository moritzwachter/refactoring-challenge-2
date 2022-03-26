# Single-File Chatbot: Refactoring Challenge
## Intro
One of our graduate apprentices wrote an object-oriented chatbot using BotMan. I used the occasion and undid the 
OOP-part for this refactoring challenge. Right now, everything is done in one single file.

## Task
Refactor the code in such a way, that it is:
* testable
* object-oriented
* reusable
* configurable
* extendable

## Rules
* Don't use a framework
* Write tests
* Use 3rd party libraries where needed

## How to run
You can use the `docker-compose` file to run the PHP container:
```shell
# to start the web server and access bash
$ docker-compose run --rm php-cli
```

To test the Slack connection, you can create a new Slack Bot and configure the Webhook-URL to your NGROK container URL.

https://slack.com/help/articles/115005265703-Create-a-bot-for-your-workspace

https://ngrok.com/

The ngrok container can be started via docker-compose.

## Note
This is a refactoring challenge that is covered in a series of blog posts at: https://moritzwachter.de/
