<?php
// ============================================
// SUBHAN PRINTERS - Input Validation
// ============================================

class Validator {
    private $data = [];
    private $errors = [];
    private $rules = [];
    private $customMessages = [];
    
    /**
     * Constructor
     */
    public function __construct($data = []) {
        $this->data = $data;
    }
    
    /**
     * Set data to validate
     */
    public function setData($data): self {
        $this->data = $data;
        return $this;
    }
    
    /**
     * Add validation rules
     */
    public function rules($rules): self {
        $this->rules = $rules;
        return $this;
    }
    
    /**
     * Set custom error messages
     */
    public function messages($messages): self {
        $this->customMessages = $messages;
        return $this;
    }
    
    /**
     * Run validation
     */
    public function validate(): bool {
        $this->errors = [];
        
        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            $value = $this->data[$field] ?? null;
            
            foreach ($rules as $rule) {
                $this->applyRule($field, $value, $rule);
            }
        }
        
        return empty($this->errors);
    }
    
    /**
     * Apply a single rule
     */
    private function applyRule($field, $value, $rule): void {
        $ruleName = $rule;
        $parameter = null;
        
        // Check for parameter (e.g., min:5)
        if (strpos($rule, ':') !== false) {
            list($ruleName, $parameter) = explode(':', $rule, 2);
        }
        
        $method = 'validate' . ucfirst($ruleName);
        
        if (method_exists($this, $method)) {
            $result = $this->$method($field, $value, $parameter);
            if (!$result) {
                $this->addError($field, $ruleName, $parameter);
            }
        }
    }
    
    /**
     * Add error message
     */
    private function addError($field, $rule, $parameter = null): void {
        // Check custom message first
        $customKey = $field . '.' . $rule;
        if (isset($this->customMessages[$customKey])) {
            $message = $this->customMessages[$customKey];
        } else {
            $message = $this->getDefaultMessage($rule, $field, $parameter);
        }
        
        $this->errors[$field][] = $message;
    }
    
    /**
     * Get default error message
     */
    private function getDefaultMessage($rule, $field, $parameter = null): string {
        $fieldName = ucwords(str_replace('_', ' ', $field));
        $messages = [
            'required' => "The $fieldName field is required.",
            'email' => "Please enter a valid email address.",
            'min' => "The $fieldName must be at least $parameter characters.",
            'max' => "The $fieldName must not exceed $parameter characters.",
            'between' => "The $fieldName must be between $parameter characters.",
            'numeric' => "The $fieldName must be a number.",
            'integer' => "The $fieldName must be an integer.",
            'url' => "Please enter a valid URL.",
            'phone' => "Please enter a valid phone number.",
            'date' => "Please enter a valid date.",
            'after' => "The date must be after $parameter.",
            'before' => "The date must be before $parameter.",
            'in' => "The selected $fieldName is invalid.",
            'not_in' => "The selected $fieldName is invalid.",
            'same' => "The $fieldName confirmation does not match.",
            'different' => "The $fieldName must be different.",
            'regex' => "The $fieldName format is invalid.",
            'file' => "Please upload a valid file.",
            'image' => "Please upload an image file.",
            'mimes' => "The file must be a type: $parameter.",
            'max_size' => "The file size must not exceed $parameter KB.",
            'exists' => "The selected $fieldName does not exist.",
            'unique' => "The $fieldName has already been taken.",
        ];
        
        return $messages[$rule] ?? "The $fieldName field is invalid.";
    }
    
    // ============================================================
    // VALIDATION RULES
    // ============================================================
    
    /**
     * Required rule
     */
    private function validateRequired($field, $value): bool {
        if (is_null($value)) return false;
        if (is_string($value)) return trim($value) !== '';
        return !empty($value);
    }
    
    /**
     * Email rule
     */
    private function validateEmail($field, $value): bool {
        return empty($value) || filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Min length rule
     */
    private function validateMin($field, $value, $parameter): bool {
        if (empty($value)) return true;
        return strlen($value) >= (int)$parameter;
    }
    
    /**
     * Max length rule
     */
    private function validateMax($field, $value, $parameter): bool {
        if (empty($value)) return true;
        return strlen($value) <= (int)$parameter;
    }
    
    /**
     * Between rule
     */
    private function validateBetween($field, $value, $parameter): bool {
        if (empty($value)) return true;
        list($min, $max) = explode(',', $parameter);
        $length = strlen($value);
        return $length >= (int)$min && $length <= (int)$max;
    }
    
    /**
     * Numeric rule
     */
    private function validateNumeric($field, $value): bool {
        return empty($value) || is_numeric($value);
    }
    
    /**
     * Integer rule
     */
    private function validateInteger($field, $value): bool {
        return empty($value) || filter_var($value, FILTER_VALIDATE_INT) !== false;
    }
    
    /**
     * URL rule
     */
    private function validateUrl($field, $value): bool {
        return empty($value) || filter_var($value, FILTER_VALIDATE_URL) !== false;
    }
    
    /**
     * Phone rule (Pakistan)
     */
    private function validatePhone($field, $value): bool {
        if (empty($value)) return true;
        $phone = preg_replace('/[^0-9]/', '', $value);
        return preg_match('/^(03[0-9]{9}|0[1-9][0-9]{7,11})$/', $phone);
    }
    
    /**
     * Date rule
     */
    private function validateDate($field, $value): bool {
        return empty($value) || strtotime($value) !== false;
    }
    
    /**
     * Date after rule
     */
    private function validateAfter($field, $value, $parameter): bool {
        if (empty($value)) return true;
        $date = strtotime($value);
        $compare = strtotime($parameter);
        return $date > $compare;
    }
    
    /**
     * Date before rule
     */
    private function validateBefore($field, $value, $parameter): bool {
        if (empty($value)) return true;
        $date = strtotime($value);
        $compare = strtotime($parameter);
        return $date < $compare;
    }
    
    /**
     * In rule (allowed values)
     */
    private function validateIn($field, $value, $parameter): bool {
        if (empty($value)) return true;
        $allowed = explode(',', $parameter);
        return in_array($value, $allowed);
    }
    
    /**
     * Not in rule (disallowed values)
     */
    private function validateNotIn($field, $value, $parameter): bool {
        if (empty($value)) return true;
        $disallowed = explode(',', $parameter);
        return !in_array($value, $disallowed);
    }
    
    /**
     * Same rule (confirmation field)
     */
    private function validateSame($field, $value, $parameter): bool {
        if (empty($value)) return true;
        return $value === ($this->data[$parameter] ?? null);
    }
    
    /**
     * Different rule
     */
    private function validateDifferent($field, $value, $parameter): bool {
        if (empty($value)) return true;
        return $value !== ($this->data[$parameter] ?? null);
    }
    
    /**
     * Regex rule
     */
    private function validateRegex($field, $value, $parameter): bool {
        if (empty($value)) return true;
        return preg_match('/' . $parameter . '/', $value) === 1;
    }
    
    /**
     * File rule (check if file was uploaded)
     */
    private function validateFile($field, $value): bool {
        if (!isset($_FILES[$field])) return false;
        return $_FILES[$field]['error'] === UPLOAD_ERR_OK;
    }
    
    /**
     * Image rule
     */
    private function validateImage($field, $value): bool {
        if (!isset($_FILES[$field])) return false;
        $type = $_FILES[$field]['type'];
        return strpos($type, 'image/') === 0;
    }
    
    /**
     * MIME types rule
     */
    private function validateMimes($field, $value, $parameter): bool {
        if (!isset($_FILES[$field])) return false;
        $allowed = explode(',', $parameter);
        $extension = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
        return in_array($extension, $allowed);
    }
    
    /**
     * Max file size rule (in KB)
     */
    private function validateMaxSize($field, $value, $parameter): bool {
        if (!isset($_FILES[$field])) return false;
        $sizeKB = $_FILES[$field]['size'] / 1024;
        return $sizeKB <= (int)$parameter;
    }
    
    /**
     * Exists rule (check database)
     */
    private function validateExists($field, $value, $parameter): bool {
        if (empty($value)) return true;
        list($table, $column) = explode(',', $parameter);
        
        $db = getDB();
        $sql = "SELECT 1 FROM $table WHERE $column = ? LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([$value]);
        return $stmt->fetch() !== false;
    }
    
    /**
     * Unique rule (check database)
     */
    private function validateUnique($field, $value, $parameter): bool {
        if (empty($value)) return true;
        list($table, $column) = explode(',', $parameter);
        
        $db = getDB();
        $sql = "SELECT 1 FROM $table WHERE $column = ? LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([$value]);
        return $stmt->fetch() === false;
    }
    
    // ============================================================
    // PUBLIC METHODS
    // ============================================================
    
    /**
     * Get all errors
     */
    public function getErrors(): array {
        return $this->errors;
    }
    
    /**
     * Get all errors as a single string
     */
    public function getErrorsString(): string {
        $messages = [];
        foreach ($this->errors as $fieldErrors) {
            foreach ($fieldErrors as $error) {
                $messages[] = $error;
            }
        }
        return implode(', ', $messages);
    }
    
    /**
     * Get errors for a specific field
     */
    public function getError($field): ?string {
        return $this->errors[$field][0] ?? null;
    }
    
    /**
     * Check if field has error
     */
    public function hasError($field): bool {
        return isset($this->errors[$field]) && !empty($this->errors[$field]);
    }
    
    /**
     * Get validated data
     */
    public function getValidated(): array {
        $validated = [];
        foreach ($this->rules as $field => $ruleString) {
            if (isset($this->data[$field])) {
                $validated[$field] = $this->data[$field];
            }
        }
        return $validated;
    }
    
    /**
     * Validate and return validated data
     */
    public function validated(): array {
        if ($this->validate()) {
            return $this->getValidated();
        }
        return [];
    }
}

// ============================================================
// HELPER FUNCTIONS
// ============================================================

/**
 * Validate data with rules
 */
function validate($data, $rules, $messages = []): Validator {
    $validator = new Validator($data);
    $validator->rules($rules)->messages($messages);
    return $validator;
}

/**
 * Quick validation with automatic error handling
 */
function validate_or_fail($data, $rules, $messages = [], $redirect = null): array {
    $validator = validate($data, $rules, $messages);
    
    if (!$validator->validate()) {
        if (is_ajax()) {
            json_error($validator->getErrorsString());
        }
        
        set_flash('validation_errors', $validator->getErrors());
        set_flash('old_input', $data);
        
        if ($redirect) {
            redirect($redirect);
        } else {
            redirect_back();
        }
    }
    
    return $validator->getValidated();
}

/**
 * Get old input value
 */
function old($key, $default = '') {
    $old = get_flash('old_input') ?? [];
    return $old[$key] ?? $default;
}

/**
 * Show validation error for a field
 */
function error($key, $format = '<div class="error">%s</div>') {
    $errors = get_flash('validation_errors') ?? [];
    if (isset($errors[$key])) {
        return sprintf($format, $errors[$key][0]);
    }
    return '';
}

/**
 * Check if validation error exists for a field
 */
function has_error($key): bool {
    $errors = get_flash('validation_errors') ?? [];
    return isset($errors[$key]);
}