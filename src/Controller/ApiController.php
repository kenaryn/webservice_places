<?php
declare(strict_types=1);
namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiController extends AbstractController
{
   /**
    * @var integer HTTP statuc code - 200 (OK) by default
    */
   protected ?int $statusCode = 200;


   /**
    * Get the value of statusCode
    * @return integer
    */
   public function getStatusCode(): ?int
   {
      return $this->statusCode;
   }

   /**
    * Set the value of statusCode
    * @param integer $statusCode the status code
    * 
    * @return self
    */
   protected function setStatusCode(int $statusCode): static
   {
      $this->statusCode = $statusCode;
      return $this;
   }

   /**
    * Returns a JSON response
    * @param array $data  
    * @param array $headers
    * 
    * @return jsonResponse
    */
   public function response(array $data = [], array $headers = [])
   {
      return new JsonResponse($data, $this->getStatusCode(), $headers);
   }

   /**
    * Sets an error message and returns a JSON response
    * @param string $errors
    * @param $headers
    *
    * @return JsonResponse
    */
    public function respondWithErrors(string $errors, array $headers = []): JsonResponse
    {
      $data = [
         'status' => $this->getStatusCode(),
         'errors' => $errors,
      ];

      return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Sets an message message and returns a JSON response
     * 
     * @param string $success
     * @param array $headers
     * 
     * @return JsonResponse
     */
    public function respondWithSuccess(string $success = '', array $headers = []): JsonResponse
    {
      $data = [
         'status' => $this->getStatusCode(),
         'success' => $success,
      ];
      return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Returns a 401 Unauthorized HTTP response
     * 
     * @param string $message
     * 
     * @return JsonResponse
     */
   public function responsdUnauthorized(string $message = 'Not authorized!'): JsonResponse
   {
      return $this->setStatusCode(401)->respondWithErrors($message);
   }

   /**
    * Returns a 422 unprocessable entity
    * 
    * @param string $message
    *
    * @return JsonResponse
    */
   public function respondValidationError(string $message = 'Validation-based error.'): JsonResponse
   {
   return $this->setStatusCode(422)->respondWithErrors($message);
   }

    /**
     * Returns a 404 not found
     * @param string $message
     * 
     * @return JsonResponse
     */
   public function respondNotFound(string $message = 'Resource not found'): JsonResponse
   {
      return $this->setStatusCode(404)->respondWithErrors($message);
   }

   /**
    * Returns a 201 Created
    * @param array $data
    *
    * @return JsonResponse
    */
   public function respondCreated(array $data = []): JsonResponse
   {
      return $this->setStatusCode(201)->response($data);
   }

   /**
    * Accepts JSON payloads in POST requests.
    */
   protected function transformJsonBody(\Symfony\Component\HttpFoundation\Request $request): \Symfony\Component\HttpFoundation\Request
   {
      $data = json_decode($request->getContent(), true);
      if ($data === null) {
         return $request;
      }

      $request->request->replace($data);

      return $request;
   }
}
?>