
<?php

/**
 * Простая проверка входных данных.
 * Пример: $v = new Validator($_POST); $v->required('email')->email('email');
 */
class Validator
{
    private array $data;
    private array $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function required(string $field, ?string $message = null): self
    {
        if (!isset($this->data[$field]) || trim((string)$this->data[$field]) === '') {
            $this->errors[$field] = $message ?? 'Поле обязательно для заполнения';
        }
        return $this;
    }

    public function email(string $field): self
    {
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = 'Некорректный email';
        }
        return $this;
    }

    public function minLength(string $field, int $length): self
    {
        if (isset($this->data[$field]) && mb_strlen((string)$this->data[$field]) < $length) {
            $this->errors[$field] = "Минимальная длина — $length символов";
        }
        return $this;
    }

    public function maxLength(string $field, int $length): self
    {
        if (isset($this->data[$field]) && mb_strlen((string)$this->data[$field]) > $length) {
            $this->errors[$field] = "Максимальная длина — $length символов";
        }
        return $this;
    }

    public function in(string $field, array $values): self
    {
        if (isset($this->data[$field]) && !in_array($this->data[$field], $values, true)) {
            $this->errors[$field] = 'Недопустимое значение';
        }
        return $this;
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function first(): ?string
    {
        return reset($this->errors) ?: null;
    }
}
