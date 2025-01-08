<?php

/**
 * @version 2.0.0
 */

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector;
use Rector\CodeQuality\Rector\Foreach_\ForeachToInArrayRector;
use Rector\CodeQuality\Rector\FuncCall\ArrayMergeOfNonArraysToSimpleArrayRector;
use Rector\CodeQuality\Rector\If_\CombineIfRector;
use Rector\CodingStyle\Rector\ClassMethod\NewlineBeforeNewAssignSetRector;
use Rector\CodingStyle\Rector\If_\NullableCompareToNullRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\CodingStyle\Rector\String_\SymplifyQuoteEscapeRector;
use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Naming\Rector\Foreach_\RenameForeachValueVariableToMatchMethodCallReturnTypeRector;
use Rector\Php81\Rector\Class_\SpatieEnumClassToEnumRector;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Symfony\CodeQuality\Rector\BinaryOp\ResponseStatusCodeRector;
use Rector\Symfony\CodeQuality\Rector\ClassMethod\ActionSuffixRemoverRector;
use Rector\Symfony\CodeQuality\Rector\ClassMethod\ResponseReturnTypeControllerActionRector;
use Rector\Symfony\CodeQuality\Rector\MethodCall\LiteralGetToRequestClassConstantRector;
use Rector\Symfony\Set\SymfonySetList;
use Rector\Symfony\Symfony26\Rector\MethodCall\RedirectToRouteRector;
use Rector\Symfony\Symfony30\Rector\ClassMethod\GetRequestRector;
use Rector\Symfony\Symfony30\Rector\MethodCall\ChangeStringCollectionOptionToConstantRector;
use Rector\Symfony\Symfony34\Rector\ClassMethod\ReplaceSensioRouteAnnotationWithSymfonyRector;
use Rector\Symfony\Symfony51\Rector\ClassMethod\CommandConstantReturnCodeRector;
use Rector\Symfony\Symfony53\Rector\StaticPropertyFetch\KernelTestCaseContainerPropertyDeprecationRector;
use Rector\Symfony\Symfony60\Rector\MethodCall\GetHelperControllerToServiceRector;
use Rector\Symfony\Symfony61\Rector\Class_\CommandPropertyToAttributeRector;
use Rector\Symfony\Symfony62\Rector\ClassMethod\ParamConverterAttributeToMapEntityAttributeRector;
use Rector\Symfony\Symfony62\Rector\MethodCall\SimplifyFormRenderingRector;
use Rector\Transform\Rector\Attribute\AttributeKeyToClassConstFetchRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictSetUpRector;

$rectorConfig = RectorConfig::configure()
    ->withRootFiles()
    ->withPaths([
        __DIR__.'/DependencyInjection',
        __DIR__.'/Enums',
        __DIR__.'/Faker',
        __DIR__.'/PhpUnit',
        __DIR__.'/Resources',
        __DIR__.'/Twig',
        __DIR__.'/Tests',
    ])
    ->withSets([
        DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
        PHPUnitSetList::PHPUNIT_90,
        SymfonySetList::SYMFONY_60,
        SymfonySetList::SYMFONY_61,
        SymfonySetList::SYMFONY_62,
        SymfonySetList::SYMFONY_63,
        SymfonySetList::SYMFONY_64,
        SymfonySetList::SYMFONY_71,
        SymfonySetList::SYMFONY_72,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
    ])
    ->withAttributesSets(all: true)
    ->withRules([
        // Code Quality
        ArrayMergeOfNonArraysToSimpleArrayRector::class, // https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md#arraymergeofnonarraystosimplearrayrector
        ForeachToInArrayRector::class, // https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md#foreachtoinarrayrector
        InlineArrayReturnAssignRector::class, // https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md#inlinearrayreturnassignrector
        InlineConstructorDefaultToPropertyRector::class, // https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md#inlineconstructordefaulttopropertyrector
        CombineIfRector::class, // https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md#combineifrector

        // Coding Style
        NewlineAfterStatementRector::class, // https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md#newlineafterstatementrector
        NewlineBeforeNewAssignSetRector::class, // https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md#newlinebeforenewassignsetrector
        NullableCompareToNullRector::class, // https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md#nullablecomparetonullrector
        SymplifyQuoteEscapeRector::class, // https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md#symplifyquoteescaperector

        // Naming
        RenameForeachValueVariableToMatchMethodCallReturnTypeRector::class, // https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md#renameforeachvaluevariabletomatchmethodcallreturntyperector

        // PHP 8.1
        ReadOnlyPropertyRector::class,
        SpatieEnumClassToEnumRector::class, // https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md#spatieenumclasstoenumrector

        // Type Declaration
        TypedPropertyFromStrictConstructorRector::class, // https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md#typedpropertyfromstrictconstructorrector
        TypedPropertyFromStrictSetUpRector::class, // https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md#typedpropertyfromstrictsetuprector

        // Symfony
        CommandConstantReturnCodeRector::class,
        CommandPropertyToAttributeRector::class,
        ChangeStringCollectionOptionToConstantRector::class,
        GetHelperControllerToServiceRector::class,
        GetRequestRector::class,
        KernelTestCaseContainerPropertyDeprecationRector::class,
        LiteralGetToRequestClassConstantRector::class,
        ParamConverterAttributeToMapEntityAttributeRector::class,
        RedirectToRouteRector::class,
        ReplaceSensioRouteAnnotationWithSymfonyRector::class,
        ResponseReturnTypeControllerActionRector::class,
        ResponseStatusCodeRector::class,
        SimplifyFormRenderingRector::class,

        // Transform
        AttributeKeyToClassConstFetchRector::class,
    ])
    ->withSkip([
        ActionSuffixRemoverRector::class,
    ]);

if (!str_contains(strtolower(php_uname('s')), 'window')) {
    $rectorConfig->withParallel();
}

return $rectorConfig;
