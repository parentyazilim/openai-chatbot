<?php

namespace OpenAIChatbot;

class OpenAIChatbot
{
	
	private $apiKey;
	private $engineId;
	private $apiUrl = 'https://api.openai.com/v1/engines';
	
	public function __construct($apiKey, $engineId = 'davinci')
	{
		$this->apiKey = $apiKey;
		$this->engineId = $engineId;
	}
	
	/**
	 * Ask a question to the OpenAI chatbot
	 *
	 * @param string $question The question to ask the chatbot
	 *
	 * @return string The chatbot's answer to the question
	 */
	public function ask($question)
	{
		$url = $this->apiUrl . '/' . $this->engineId . '/completions';
		$data = array(
			'prompt' => $question,
			'max_tokens' => 50,
			'temperature' => 0.7,
			'n' => 1,
			'stop' => '.'
		);
		$options = array(
			'http' => array(
				'header' => "Content-type: application/json\r\nAuthorization: Bearer $this->apiKey\r\n",
				'method' => 'POST',
				'content' => json_encode($data)
			)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$response = json_decode($result);
		$answer = $response->choices[0]->text;
		return $answer;
	}
	
	/**
	 * Search for relevant documents using the OpenAI search API
	 *
	 * @param array $documents An array of documents to search through, where each document is an associative array with "id" and "text" keys.
	 * @param string $query The search query to use
	 * @param int $maxRerank The maximum number of results to rerank
	 *
	 * @return array An array of search results, where each result is an associative array with "document" and "score" keys.
	 */
	public function search($documents, $query, $maxRerank = 200)
	{
		$url = $this->apiUrl . '/' . $this->engineId . '/search';
		$data = array(
			'documents' => $documents,
			'query' => $query,
			'max_rerank' => $maxRerank
		);
		$options = array(
			'http' => array(
				'header' => "Content-type: application/json\r\nAuthorization: Bearer $this->apiKey\r\n",
				'method' => 'POST',
				'content' => json_encode($data)
			)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$response = json_decode($result);
		$searchResults = array();
		foreach ($response->data as $item) {
			$searchResults[] = array(
				'document' => $item->document->id,
				'score' => $item->score
			);
		}
		return $searchResults;
	}
	
	/**
	 * Generate text using the OpenAI text API
	 *
	 * @param string $prompt The prompt to use for generating the text
	 * @param int $length The length of the generated text (in tokens)
	 * @param float $temperature The "creativity" of the generated text (a value between 0 and 1, with higher values producing more creative text)
	 *
	 * @return string The generated text
	 */
	public function generateText($prompt, $length = 50, $temperature = 0.7)
	{
		$url = $this->apiUrl . '/' . $this->engineId . '/completions';
		$data = array(
			'prompt' => $prompt,
			'max_tokens' => $length,
			'temperature' => $temperature
		);
		$options = array(
			'http' => array(
				'header' => "Content-type: application/json\r\nAuthorization: Bearer $this->apiKey\r\n",
				'method' => 'POST',
				'content' => json_encode($data)
			)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$response = json_decode($result);
		$generatedText = $response->choices[0]->text;
		return $generatedText;
	}
	
	/**
	 * Create a new fine-tuning session using the OpenAI API
	 *
	 * @param string $model The ID of the model to use for fine-tuning
	 * @param string $description A description of the fine-tuning session
	 *
	 * @return string The ID of the new fine-tuning session
	 */
	public function createFineTuningSession($model, $description = '')
	{
		$url = $this->apiUrl . '/' . $this->engineId . '/fine-tunes';
		$data = array(
			'model' => $model,
			'description' => $description
		);
		$options = array(
			'http' => array(
				'header' => "Content-type: application/json\r\nAuthorization: Bearer $this->apiKey\r\n",
				'method' => 'POST',
				'content' => json_encode($data)
			)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$response = json_decode($result);
		$sessionId = $response->data->id;
		return $sessionId;
	}
	
	/**
	 * Upload training data to a fine-tuning session using the OpenAI API
	 *
	 * @param string $sessionId The ID of the fine-tuning session
	 * @param array $data An array of training data, where each item is an associative array with "text" and "label" keys.
	 *
	 * @return bool true on success, false on failure
	 */
	public function uploadTrainingData($sessionId, $data)
	{
		$url = $this->apiUrl . '/' . $this->engineId . '/fine-tunes/' . $sessionId . '/data';
		$options = array(
			'http' => array(
				'header' => "Content-type: application/json\r\nAuthorization: Bearer $this->apiKey\r\n",
				'method' => 'POST',
				'content' => json_encode($data)
			)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$response = json_decode($result);
		if (isset($response->error)) {
			return false;
		}
		return true;
	}
	
	/**
	 * Start training a fine-tuning session using the OpenAI API
	 *
	 * @param string $sessionId The ID of the fine-tuning session
	 * @param array $trainingConfig An associative array containing the training configuration options, including "epochs", "batch_size", and "learning_rate"
	 *
	 * @return bool true on success, false on failure
	 */
	public function startFineTuning($sessionId, $trainingConfig)
	{
		$url = $this->apiUrl . '/' . $this->engineId . '/fine-tunes/' . $sessionId . '/train';
		$options = array(
			'http' => array(
				'header' => "Content-type: application/json\r\nAuthorization: Bearer $this->apiKey\r\n",
				'method' => 'POST',
				'content' => json_encode($trainingConfig)
			)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$response = json_decode($result);
		if (isset($response->error)) {
			return false;
		}
		return true;
	}
	
	/**
	 * Check the status of a fine-tuning session using the OpenAI API
	 *
	 * @param string $sessionId The ID of the fine-tuning session
	 *
	 * @return string The status of the fine-tuning session
	 */
	public function checkFineTuningStatus($sessionId)
	{
		$url = $this->apiUrl . '/' . $this->engineId . '/fine-tunes/' . $sessionId . '/status';
		$options = array(
			'http' => array(
				'header' => "Content-type: application/json\r\nAuthorization: Bearer $this->apiKey\r\n",
				'method' => 'GET'
			)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$response = json_decode($result);
		$status = $response->data->status;
		return $status;
	}
	
	/**
	 * Get the fine-tuned model from a fine-tuning session using the OpenAI API
	 *
	 * @param string $sessionId The ID of the fine-tuning session
	 *
	 * @return string The ID of the fine-tuned model
	 */
	public function getFineTunedModel($sessionId)
	{
		$url = $this->apiUrl . '/' . $this->engineId . '/fine-tunes/' . $sessionId . '/model';
		$options = array(
			'http' => array(
				'header' => "Content-type: application/json\r\nAuthorization: Bearer $this->apiKey\r\n",
				'method' => 'GET'
			)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$response = json_decode($result);
		$modelId = $response->data->id;
		return $modelId;
	}
}
