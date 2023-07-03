<?php

declare(strict_types=1);

namespace Controllers;

class ErrorHandler
{
    /**
     * @param mixed $errors
     * @return string
     */
  public static function errorPopup(mixed $errors): string
  {
    if (is_array($errors)) {
      foreach ($errors as $values) {
        $errors = $values;
      }
    }

    $errorMessage = '<div class="error-popup">
    <div class="error-message">
      ' . $errors . '
      <span class="close-btn">&times;</span>
    </div>
  </div>
  <script>
    const errorPopup = document.querySelector(".error-popup");
    const closeBtn = errorPopup.querySelector(".close-btn");

    closeBtn.addEventListener("click", function() {
      errorPopup.style.display = "none";
    });
  </script>
  <style>
  .error-popup {padding-top:1px;position: fixed;top: 0;left:0;width:100%;height:100%;
    background-color:rgba(0, 0, 0, 0.5);z-index:9999;display:flex;align-items:center;
    justify-content:center;}.error-message{display:flex;flex-direction:column;
    background-color:#f44336;color:black;padding:30px;border-radius:5px;
    position:relative;}.error-messagediv:nth-child(2){background-color:blue;}
    .error-message p{margin:0;padding-left:1rem;padding-right:1rem;padding-bottom:0.3rem;
    padding-top:0.3rem;background-color:#f44336;color:white;font-weight:bold;}
    .close-btn{padding-top:1px;color:red;background-color:white;padding-left:7px;
    padding-right:7px;border-radius:4px;position:absolute;top:10px;right:10px;
    font-size:24px;font-weight:bold;cursor:pointer;}.close-btn:hover{color:black;
    background-color:lightgrey;}
  </style>';

    return $errorMessage;
  }
}
