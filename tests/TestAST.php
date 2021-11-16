<?php


use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;


final class ChangeMethodNameNodeVisitor extends \PhpParser\NodeVisitorAbstract
{
    public function enterNode(\PhpParser\Node $node)
    {
        var_dump(get_class($node));

        if (! $node instanceof \PhpParser\Node\Expr\Array_) {
            return $node;
        }
        /** @var \PhpParser\Node\Expr\Array_ $currentNode */
        $currentNode = $node;

//        var_dump($currentNode->getSubNodeNames());
//        $currentNode->items[] = new \PhpParser\Node\Expr\ArrayItem(
//            new \PhpParser\Node\Scalar\String_('baz'), new \PhpParser\Node\Scalar\String_('baz')
//        );

//        var_dump($currentNode->value);

//        $currentNode->name = new \PhpParser\Node\Name('ClassB');

        // return node to tell parser to modify it
        return $node;
    }
}

$code = <<<'CODE'
<?php

class B
{
    protected array $dif = [
        'Nome' => 'Alberto'
    ];
}
CODE;

$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
try {
    $ast = $parser->parse($code);


    $nodeTraverser = new \PhpParser\NodeTraverser();
    $nodeTraverser->addVisitor(new ChangeMethodNameNodeVisitor());
    $traversedNodes = $nodeTraverser->traverse($ast);

    $prettyPrinter = new PrettyPrinter\Standard;
    echo $prettyPrinter->prettyPrintFile($traversedNodes);

} catch (Error $error) {
    echo "Parse error: {$error->getMessage()}\n";
    return;
}

die();
