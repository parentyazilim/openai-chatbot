OpenAI Chatbot
==============

This is a PHP class for interacting with OpenAI's API, specifically for their chatbot, search, and text-generation functionalities, as well as for creating and fine-tuning machine learning models using their API.

The class has the following public methods:

-   `__construct($apiKey, $engineId = 'davinci')`: Constructor that takes an API key and an optional engine ID (default is 'davinci').
-   `ask($question)`: Method for asking the chatbot a question and getting a response.
-   `search($documents, $query, $maxRerank = 200)`: Method for searching for relevant documents using the OpenAI search API.
-   `generateText($prompt, $length = 50, $temperature = 0.7)`: Method for generating text using the OpenAI text API.
-   `createFineTuningSession($model, $description = '')`: Method for creating a new fine-tuning session using the OpenAI API.
-   `uploadTrainingData($sessionId, $data)`: Method for uploading training data to a fine-tuning session using the OpenAI API.
-   `startFineTuning($sessionId, $trainingConfig)`: Method for starting training a fine-tuning session using the OpenAI API.
-   `checkFineTuningStatus($sessionId)`: Method for checking the status of a fine-tuning session using the OpenAI API.
-   `getFineTunedModel($sessionId)`: Method for getting the fine-tuned model from a fine-tuning session using the OpenAI API.

Each of these methods interacts with the OpenAI API using HTTP requests, and returns the relevant response as a PHP object. The `ask()` and `generateText()` methods take a question or prompt as a string and return a string with the corresponding response or generated text. The `search()` method takes an array of documents to search through, a search query, and an optional maximum number of results to rerank, and returns an array of search results. The `createFineTuningSession()` method creates a new fine-tuning session and returns its ID. The `uploadTrainingData()` method uploads training data to an existing fine-tuning session and returns true on success or false on failure. The `startFineTuning()` method starts training a fine-tuning session with the given configuration options and returns true on success or false on failure. The `checkFineTuningStatus()` method checks the status of an existing fine-tuning session and returns its status as a string. The `getFineTunedModel()` method gets the fine-tuned model from an existing fine-tuning session and returns its ID as a string.

OpenAI Chatbot PHP Class
========================

The `OpenAIChatbot` class is a PHP wrapper for the [OpenAI API](https://beta.openai.com/docs/api-reference/introduction). It allows you to easily use the OpenAI API to generate text, answer questions, search through documents, and perform fine-tuning on models.

Requirements
------------

To use the `OpenAIChatbot` class, you will need:

-   PHP 7.3 or later
-   The `json` and `openssl` extensions enabled
-   An OpenAI API key

Installation
------------

You can install the `OpenAIChatbot` class using Composer:

```javascript
composer require parentyazilim/openai-chatbot
```

Usage
-----

To use the `OpenAIChatbot` class, you need to first create an instance of the class and provide your OpenAI API key. You can optionally specify a default engine ID (the default is `davinci`):

```php
<?php
use OpenAIChatbot\OpenAIChatbot;

$openai = new OpenAIChatbot('your_api_key', 'davinci');
```

### Asking Questions

To ask a question and get a response from the OpenAI chatbot, use the `ask()` function. The function takes a single parameter, the question to ask, and returns the chatbot's response:

```php
<?php
$response = $openai->ask('What is the meaning of life?');
echo $response;
```

### Searching through Documents

To search through a set of documents and get the most relevant results, use the `search()` function. The function takes three parameters:

-   `$documents`: An array of documents to search through, where each document is an associative array with "id" and "text" keys.
-   `$query`: The search query to use.
-   `$maxRerank` (optional): The maximum number of results to rerank (default is 200).

The function returns an array of search results, where each result is an associative array with "document" and "score" keys:

```php
<?php
$documents = [
    ['id' => 'doc1', 'text' => 'The quick brown fox jumped over the lazy dog'],
    ['id' => 'doc2', 'text' => 'The brown fox is quick and the dog is lazy'],
    ['id' => 'doc3', 'text' => 'The quick brown fox is friends with the lazy dog'],
];
$results = $openai->search($documents, 'quick brown fox');
foreach ($results as $result) {
    echo $result['document'] . ' (' . $result['score'] . ')' . PHP_EOL;
}
```

### Generating Text

To generate text using the OpenAI API, use the `generateText()` function. The function takes three parameters:

-   `$prompt`: The prompt to use for generating the text.
-   `$length` (optional): The length of the generated text (in tokens, default is 50).
-   `$temperature` (optional): The "creativity" of the generated text (a value between 0 and 1, with higher values producing more creative text, default is 0.7).

The function returns the generated text:

```php
<?php
$text = $openai->generateText('Once upon a time', 100, 0.5);
echo $text;`
```

### Fine-tuning a Model

To fine-tune an OpenAI model, you need to first create a fine-tuning session using the `createFineTuningSession()` function. The function takes two parameters:

-   `$model`: The ID of the model to use for fine-tuning.
-   `$description` (optional) The description of the fine-tuning session (default is an empty string).

The function returns the ID of the new fine-tuning session:

```php
<?php
$sessionId = $openai->createFineTuningSession('text-davinci-002', 'My fine-tuning session');
echo $sessionId;
```

Once you have created a fine-tuning session, you can upload training data using the `uploadTrainingData()` function. The function takes two parameters:

-   `$sessionId`: The ID of the fine-tuning session.
-   `$data`: An array of training data, where each item is an associative array with "text" and "label" keys.

The function returns `true` on success and `false` on failure:

```php
<?php

$data = [
    ['text' => 'The quick brown fox', 'label' => 'animal'],
    ['text' => 'The lazy dog', 'label' => 'animal'],
    ['text' => 'The big red ball', 'label' => 'object'],
];

$result = $openai->uploadTrainingData($sessionId, $data);

if ($result) {
    echo 'Training data uploaded successfully' . PHP_EOL;
} else {
    echo 'Failed to upload training data' . PHP_EOL;
}
```

Once you have uploaded training data, you can start the fine-tuning process using the `startFineTuning()` function. The function takes two parameters:

-   `$sessionId`: The ID of the fine-tuning session.
-   `$trainingConfig`: An associative array containing the training configuration options, including "epochs", "batch_size", and "learning_rate".

The function returns `true` on success and `false` on failure:

```php
<?php

$trainingConfig = [
    'epochs' => 2,
    'batch_size' => 32,
    'learning_rate' => 0.001,
];

$result = $openai->startFineTuning($sessionId, $trainingConfig);

if ($result) {
    echo 'Fine-tuning started successfully' . PHP_EOL;
} else {
    echo 'Failed to start fine-tuning' . PHP_EOL;
}
```

You can check the status of the fine-tuning process using the `checkFineTuningStatus()` function. The function takes one parameter:

-   `$sessionId`: The ID of the fine-tuning session.

The function returns the status of the fine-tuning session:

```php
<?php
$status = $openai->checkFineTuningStatus($sessionId);
echo 'Status: ' . $status . PHP_EOL;
```

Finally, once the fine-tuning process is complete, you can get the ID of the fine-tuned model using the `getFineTunedModel()` function. The function takes one parameter:

-   `$sessionId`: The ID of the fine-tuning session.

The function returns the ID of the fine-tuned model:

```php
<?php
$modelId = $openai->getFineTunedModel($sessionId);
echo 'Fine-tuned model ID: ' . $modelId . PHP_EOL;
```

Error Handling
--------------

If an error occurs while using the `OpenAIChatbot` class, the functions will return `false`. You can check for errors by calling the `error()` function:

```php
<?php

if ($openai->error()) {
    echo 'An error occurred: ' . $openai->errorMessage();
}
```

Conclusion
----------

The `OpenAIChatbot` class provides an easy-to-use PHP wrapper for the OpenAI API, allowing you to quickly and easily generate text, answer questions, search through documents, and perform fine-tuning on models.

License
-------

This project is licensed under the MIT License. See the [LICENSE](https://chat.openai.com/chat/LICENSE) file for details.
