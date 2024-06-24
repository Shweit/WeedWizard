<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = (new Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new Config())
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        '@PhpCsFixer' => true,
        '@DoctrineAnnotation' => true,
        'array_syntax' => ['syntax' => 'short'],
        'cast_spaces' => ['space' => 'none'],
        'concat_space' => ['spacing' => 'one'],
        'yoda_style' => false,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        // 'method_argument_space' => null,
        // 'no_whitespace_in_blank_line' => null,
        // 'no_extra_blank_lines' => null,
        // 'braces' => null,
        'blank_line_before_statement' => true,
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_var_without_name' => false,
        'phpdoc_types_order' => false,
        'phpdoc_order' => false,
        'phpdoc_separation' => false,
        'phpdoc_no_empty_return' => false,
        'phpdoc_add_missing_param_annotation' => false,
        // 'no_superfluous_elseif' => null,
        'class_definition' => false,
        'ternary_to_null_coalescing' => true,
        'php_unit_test_class_requires_covers' => false,
        'php_unit_internal_class' => false,
        'phpdoc_to_comment' => false,
        'single_line_comment_style' => false,
        'global_namespace_import' => [
            'import_classes' => null,
        ],
        'no_unused_imports' => true,
        'multiline_whitespace_before_semicolons' => false,
        'phpdoc_no_useless_inheritdoc' => false,
        'no_superfluous_phpdoc_tags' => false,
        'no_useless_concat_operator' => false,
        'no_unneeded_control_parentheses' => false,
        'self_static_accessor' => false,
        'method_chaining_indentation' => false,
    ])
    ->setFinder($finder)
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
;
