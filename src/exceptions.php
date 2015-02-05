<?php

namespace Carrooi\Labels;

class RuntimeException extends \RuntimeException {}

class InvalidArgumentException extends \InvalidArgumentException {}

class InvalidStateException extends RuntimeException {}

class ConfigurationException extends RuntimeException {}

class RelationsException extends RuntimeException {}

class DuplicateLabelNamespace extends RuntimeException {}
