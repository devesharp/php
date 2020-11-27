<?php

namespace Tests\CRUD;

use Devesharp\CRUD\Repository\RepositoryMysql;
use Illuminate\Support\Facades\DB;
use Tests\CRUD\Mocks\ModelRepositoryStub;
use Tests\CRUD\Mocks\ModelStub;
use Tests\TestCase;

class RepositoryStub extends RepositoryMysql
{
    protected $model = ModelRepositoryStub::class;

    public function getModel()
    {
        return $this->modelQuery;
    }

    public function disableSoftDelete()
    {
        $this->softDelete = false;
    }
}

class RepositoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        DB::connection()->flushQueryLog();
    }

    /**
     * @testdox Mysql - Filter Int
     */
    public function testMysqlFilterWhereInt()
    {
        $repository = new RepositoryStub();
        $repository->whereInt('id', 0);
        $this->assertStringContainsString(
            ' where "id" = ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Filter IntGt
     */
    public function testMysqlFilterWhereIntGt()
    {
        $repository = new RepositoryStub();
        $repository->whereIntGt('id', 0);
        $this->assertStringContainsString(
            ' where "id" > ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Filter IntLt
     */
    public function testMysqlFilterWhereIntLt()
    {
        $repository = new RepositoryStub();
        $repository->whereIntLt('id', 0);
        $this->assertStringContainsString(
            ' where "id" < ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Filter IntGte
     */
    public function testMysqlFilterWhereIntGte()
    {
        $repository = new RepositoryStub();
        $repository->whereIntGte('id', 0);
        $this->assertStringContainsString(
            ' where "id" >= ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Filter IntLte
     */
    public function testMysqlFilterWhereIntLte()
    {
        $repository = new RepositoryStub();
        $repository->whereIntLte('id', 0);
        $this->assertStringContainsString(
            ' where "id" <= ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Filter SameString
     */
    public function testMysqlFilterWhereSameString()
    {
        $repository = new RepositoryStub();
        $repository->whereSameString('name', 'john silver');
        $this->assertStringContainsString(
            ' where "name" = ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Filter Like
     */
    public function testMysqlFilterWhereLike()
    {
        $repository = new RepositoryStub();
        $repository->whereLike('name', 'john silver');
        $this->assertStringContainsString(
            ' where "name" LIKE ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Filter NotLike
     */
    public function testMysqlFilterWhereNotLike()
    {
        $repository = new RepositoryStub();
        $repository->whereNotLike('name', 'john silver');
        $this->assertStringContainsString(
            ' where "name" NOT LIKE ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Filter BeginWithLike
     */
    public function testMysqlFilterWhereBeginWithLike()
    {
        $repository = new RepositoryStub();
        $repository->whereBeginWithLike('name', 'john silver');
        $this->assertStringContainsString(
            ' where "name" LIKE ?',
            $repository->getModel()->toSql(),
        );
        $this->assertEquals(
            $repository->getModel()->getBindings()[0],
            'john silver%',
        );
    }

    /**
     * @testdox Mysql - Filter EndWithLike
     */
    public function testMysqlFilterWhereEndWithLike()
    {
        $repository = new RepositoryStub();
        $repository->whereEndWithLike('name', 'john silver');
        $this->assertStringContainsString(
            ' where "name" LIKE ?',
            $repository->getModel()->toSql(),
        );
        $this->assertEquals(
            $repository->getModel()->getBindings()[0],
            '%john silver',
        );
    }

    /**
     * @testdox Mysql - Filter ContainsLike
     */
    public function testMysqlFilterWhereContainsLike()
    {
        $repository = new RepositoryStub();
        $repository->whereContainsLike('name', 'john silver');
        $this->assertStringContainsString(
            ' where "name" LIKE ?',
            $repository->getModel()->toSql(),
        );
        $this->assertEquals(
            $repository->getModel()->getBindings()[0],
            '%john silver%',
        );
    }

    /**
     * @testdox Mysql - Filter ContainsExplodeString
     */
    public function testMysqlFilterWhereContainsExplodeString()
    {
        $repository = new RepositoryStub();
        $repository->whereContainsExplodeString('name', 'john silver');
        $this->assertStringContainsString(
            ' where "name" LIKE ?',
            $repository->getModel()->toSql(),
        );
        $this->assertEquals(
            $repository->getModel()->getBindings()[0],
            '%john%silver%',
        );
    }

    /**
     * @testdox Mysql - Filter ArrayInt
     */
    public function testMysqlFilterWhereArrayInt()
    {
        $repository = new RepositoryStub();
        $repository->whereArrayInt('name', ['1', '2']);
        $this->assertStringContainsString(
            ' where "name" in (?, ?)',
            $repository->getModel()->toSql(),
        );
        $this->assertSame($repository->getModel()->getBindings()[0], 1);
        $this->assertSame($repository->getModel()->getBindings()[1], 2);
    }

    /**
     * @testdox Mysql - Filter ArrayNotInt
     */
    public function testMysqlFilterWhereArrayNotInt()
    {
        $repository = new RepositoryStub();
        $repository->whereArrayNotInt('name', ['1', '2']);
        $this->assertStringContainsString(
            ' where "name" not in (?, ?)',
            $repository->getModel()->toSql(),
        );
        $this->assertSame($repository->getModel()->getBindings()[0], 1);
        $this->assertSame($repository->getModel()->getBindings()[1], 2);
    }

    /**
     * @testdox Mysql - Filter ArrayString
     */
    public function testMysqlFilterWhereArrayString()
    {
        $repository = new RepositoryStub();
        $repository->whereArrayString('name', ['1', '2']);
        $this->assertStringContainsString(
            ' where "name" in (?, ?)',
            $repository->getModel()->toSql(),
        );
        $this->assertSame($repository->getModel()->getBindings()[0], '1');
        $this->assertSame($repository->getModel()->getBindings()[1], '2');
    }

    /**
     * @testdox Mysql - Filter ArrayNotString
     */
    public function testMysqlFilterWhereArrayNotString()
    {
        $repository = new RepositoryStub();
        $repository->whereArrayNotString('name', ['1', '2']);
        $this->assertStringContainsString(
            ' where "name" not in (?, ?)',
            $repository->getModel()->toSql(),
        );
        $this->assertSame($repository->getModel()->getBindings()[0], '1');
        $this->assertSame($repository->getModel()->getBindings()[1], '2');
    }

    /**
     * @testdox Mysql - Filter Or IntGt
     */
    public function testMysqlFilterOrWhereIntGt()
    {
        $repository = new RepositoryStub();
        $repository->whereInt('age', 0);
        $repository->orWhereIntGt('id', 0);
        $this->assertStringContainsString(
            ' where "age" = ? or "id" > ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Filter Or IntLt
     */
    public function testMysqlFilterOrWhereIntLt()
    {
        $repository = new RepositoryStub();
        $repository->whereInt('age', 0);
        $repository->orWhereIntLt('id', 0);
        $this->assertStringContainsString(
            ' where "age" = ? or "id" < ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Filter Or IntGte
     */
    public function testMysqlFilterOrWhereIntGte()
    {
        $repository = new RepositoryStub();
        $repository->whereInt('age', 0);
        $repository->orWhereIntGte('id', 0);
        $this->assertStringContainsString(
            ' where "age" = ? or "id" >= ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Filter Or IntLte
     */
    public function testMysqlFilterOrWhereIntLte()
    {
        $repository = new RepositoryStub();
        $repository->whereInt('age', 0);
        $repository->orWhereIntLte('id', 0);
        $this->assertStringContainsString(
            ' where "age" = ? or "id" <= ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Filter Or SameString
     */
    public function testMysqlFilterOrWhereSameString()
    {
        $repository = new RepositoryStub();
        $repository->whereInt('age', 0);
        $repository->orWhereSameString('name', 'john silver');
        $this->assertStringContainsString(
            ' where "age" = ? or "name" = ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Filter Or Like
     */
    public function testMysqlFilterOrWhereLike()
    {
        $repository = new RepositoryStub();
        $repository->whereInt('age', 0);
        $repository->orWhereLike('name', 'john silver');
        $this->assertStringContainsString(
            ' where "age" = ? or "name" LIKE ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Filter Or NotLike
     */
    public function testMysqlFilterOrWhereNotLike()
    {
        $repository = new RepositoryStub();
        $repository->whereInt('age', 0);
        $repository->orWhereNotLike('name', 'john silver');
        $this->assertStringContainsString(
            ' where "age" = ? or "name" NOT LIKE ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Filter Or BeginWithLike
     */
    public function testMysqlFilterOrWhereBeginWithLike()
    {
        $repository = new RepositoryStub();
        $repository->whereInt('age', 0);
        $repository->orWhereBeginWithLike('name', 'john silver');
        $this->assertStringContainsString(
            ' where "age" = ? or "name" LIKE ?',
            $repository->getModel()->toSql(),
        );
        $this->assertEquals(
            $repository->getModel()->getBindings()[1],
            'john silver%',
        );
    }

    /**
     * @testdox Mysql - Filter Or EndWithLike
     */
    public function testMysqlFilterOrWhereEndWithLike()
    {
        $repository = new RepositoryStub();
        $repository->whereInt('age', 0);
        $repository->orWhereEndWithLike('name', 'john silver');
        $this->assertStringContainsString(
            ' where "age" = ? or "name" LIKE ?',
            $repository->getModel()->toSql(),
        );
        $this->assertEquals(
            $repository->getModel()->getBindings()[1],
            '%john silver',
        );
    }

    /**
     * @testdox Mysql - Filter Or ContainsLike
     */
    public function testMysqlFilterOrWhereContainsLike()
    {
        $repository = new RepositoryStub();
        $repository->whereInt('age', 0);
        $repository->orWhereContainsLike('name', 'john silver');
        $this->assertStringContainsString(
            ' where "age" = ? or "name" LIKE ?',
            $repository->getModel()->toSql(),
        );
        $this->assertEquals(
            $repository->getModel()->getBindings()[1],
            '%john silver%',
        );
    }

    /**
     * @testdox Mysql - Filter Or ContainsExplodeString
     */
    public function testMysqlFilterOrWhereContainsExplodeString()
    {
        $repository = new RepositoryStub();
        $repository->whereInt('age', 0);
        $repository->orWhereContainsExplodeString('name', 'john silver');
        $this->assertStringContainsString(
            ' where "age" = ? or "name" LIKE ?',
            $repository->getModel()->toSql(),
        );
        $this->assertEquals(
            $repository->getModel()->getBindings()[1],
            '%john%silver%',
        );
    }

    /**
     * @testdox Mysql - Filter Or ArrayInt
     */
    public function testMysqlFilterOrWhereArrayInt()
    {
        $repository = new RepositoryStub();
        $repository->whereInt('age', 0);
        $repository->orWhereArrayInt('name', ['1', '2']);
        $this->assertStringContainsString(
            ' where "age" = ? or "name" in (?, ?)',
            $repository->getModel()->toSql(),
        );
        $this->assertSame($repository->getModel()->getBindings()[1], 1);
        $this->assertSame($repository->getModel()->getBindings()[2], 2);
    }

    /**
     * @testdox Mysql - Filter Or ArrayNotInt
     */
    public function testMysqlFilterOrWhereArrayNotInt()
    {
        $repository = new RepositoryStub();
        $repository->whereInt('age', 0);
        $repository->orWhereArrayNotInt('name', ['1', '2']);
        $this->assertStringContainsString(
            ' where "age" = ? or "name" not in (?, ?)',
            $repository->getModel()->toSql(),
        );
        $this->assertSame($repository->getModel()->getBindings()[1], 1);
        $this->assertSame($repository->getModel()->getBindings()[2], 2);
    }

    /**
     * @testdox Mysql - Filter Or ArrayString
     */
    public function testMysqlFilterOrWhereArrayString()
    {
        $repository = new RepositoryStub();
        $repository->whereInt('age', 0);
        $repository->orWhereArrayString('name', ['1', '2']);
        $this->assertStringContainsString(
            ' where "age" = ? or "name" in (?, ?)',
            $repository->getModel()->toSql(),
        );
        $this->assertSame($repository->getModel()->getBindings()[1], '1');
        $this->assertSame($repository->getModel()->getBindings()[2], '2');
    }

    /**
     * @testdox Mysql - Filter Or ArrayNotString
     */
    public function testMysqlFilterOrWhereArrayNotString()
    {
        $repository = new RepositoryStub();
        $repository->whereInt('age', 0);
        $repository->orWhereArrayNotString('name', ['1', '2']);
        $this->assertStringContainsString(
            ' where "age" = ? or "name" not in (?, ?)',
            $repository->getModel()->toSql(),
        );
        $this->assertSame($repository->getModel()->getBindings()[1], '1');
        $this->assertSame($repository->getModel()->getBindings()[2], '2');
    }

    /**
     * @testdox Mysql - orWhere
     */
    public function testMysqlFilterOrWhere()
    {
        $repository = new RepositoryStub();
        $repository->whereInt('id', 0)->orWhere(function (RepositoryStub $q) {
            $q->whereInt('id', 1);
            $q->whereInt('id', 2);
        });

        $this->assertStringContainsString(
            ' where "id" = ? or ("id" = ? and "id" = ?)',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - orWhere duas dimensões
     */
    public function testMysqlFilterOrWhere2()
    {
        $repository = new RepositoryStub();
        $repository->whereInt('id', 0)->orWhere(function (RepositoryStub $q) {
            $q->whereInt('id', 1);
            $q->whereInt('id', 2);

            $q->whereInt('id', 0)->orWhere(function (RepositoryStub $q) {
                $q->whereInt('id', 1);
                $q->whereInt('id', 2);
            });
        });

        $this->assertStringContainsString(
            ' or ("id" = ? and "id" = ? and "id" = ? or ("id" = ? and "id" = ?))',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - andWhere
     */
    public function testMysqlFilterAndWhere()
    {
        $repository = new RepositoryStub();
        $repository->whereInt('id', 0)->andWhere(function (RepositoryStub $q) {
            $q->whereInt('id', 1);
            $q->whereInt('id', 2);
        });

        $this->assertStringContainsString(
            ' where "id" = ? and ("id" = ? and "id" = ?)',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - andWhere duas dimensões
     */
    public function testMysqlFilterAndWhere2()
    {
        $repository = new RepositoryStub();
        $repository->whereInt('id', 0)->andWhere(function (RepositoryStub $q) {
            $q->whereInt('id', 1);
            $q->whereInt('id', 2);

            $q->whereInt('id', 0)->andWhere(function (RepositoryStub $q) {
                $q->whereInt('id', 1);
                $q->whereInt('id', 2);
            });
        });

        $this->assertStringContainsString(
            ' and ("id" = ? and "id" = ? and "id" = ? and ("id" = ? and "id" = ?))',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Having Filter Int
     */
    public function testMysqlFilterHavingInt()
    {
        $repository = new RepositoryStub();
        $repository->havingInt('id', 0);
        $this->assertStringContainsString(
            ' having "id" = ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Having Filter IntGt
     */
    public function testMysqlFilterHavingIntGt()
    {
        $repository = new RepositoryStub();
        $repository->havingIntGt('id', 0);
        $this->assertStringContainsString(
            ' having "id" > ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Having Filter IntLt
     */
    public function testMysqlFilterHavingIntLt()
    {
        $repository = new RepositoryStub();
        $repository->havingIntLt('id', 0);
        $this->assertStringContainsString(
            ' having "id" < ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Having Filter IntGte
     */
    public function testMysqlFilterHavingIntGte()
    {
        $repository = new RepositoryStub();
        $repository->havingIntGte('id', 0);
        $this->assertStringContainsString(
            ' having "id" >= ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Having Filter IntLte
     */
    public function testMysqlFilterHavingIntLte()
    {
        $repository = new RepositoryStub();
        $repository->havingIntLte('id', 0);
        $this->assertStringContainsString(
            ' having "id" <= ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Having Filter SameString
     */
    public function testMysqlFilterHavingSameString()
    {
        $repository = new RepositoryStub();
        $repository->havingSameString('name', 'john silver');
        $this->assertStringContainsString(
            ' having "name" = ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Having Filter Like
     */
    public function testMysqlFilterHavingLike()
    {
        $repository = new RepositoryStub();
        $repository->havingLike('name', 'john silver');
        $this->assertStringContainsString(
            ' having "name" LIKE ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Having Filter NotLike
     */
    public function testMysqlFilterHavingNotLike()
    {
        $repository = new RepositoryStub();
        $repository->havingNotLike('name', 'john silver');
        $this->assertStringContainsString(
            ' having "name" NOT LIKE ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Having Filter BeginWithLike
     */
    public function testMysqlFilterHavingBeginWithLike()
    {
        $repository = new RepositoryStub();
        $repository->havingBeginWithLike('name', 'john silver');
        $this->assertStringContainsString(
            ' having "name" LIKE ?',
            $repository->getModel()->toSql(),
        );
        $this->assertEquals(
            $repository->getModel()->getBindings()[0],
            'john silver%',
        );
    }

    /**
     * @testdox Mysql - Having Filter EndWithLike
     */
    public function testMysqlFilterHavingEndWithLike()
    {
        $repository = new RepositoryStub();
        $repository->havingEndWithLike('name', 'john silver');
        $this->assertStringContainsString(
            ' having "name" LIKE ?',
            $repository->getModel()->toSql(),
        );
        $this->assertEquals(
            $repository->getModel()->getBindings()[0],
            '%john silver',
        );
    }

    /**
     * @testdox Mysql - Having Filter ContainsLike
     */
    public function testMysqlFilterHavingContainsLike()
    {
        $repository = new RepositoryStub();
        $repository->havingContainsLike('name', 'john silver');
        $this->assertStringContainsString(
            ' having "name" LIKE ?',
            $repository->getModel()->toSql(),
        );
        $this->assertEquals(
            $repository->getModel()->getBindings()[0],
            '%john silver%',
        );
    }

    /**
     * @testdox Mysql - Having Filter ContainsExplodeString
     */
    public function testMysqlFilterHavingContainsExplodeString()
    {
        $repository = new RepositoryStub();
        $repository->havingContainsExplodeString('name', 'john silver');
        $this->assertStringContainsString(
            ' having "name" LIKE ?',
            $repository->getModel()->toSql(),
        );
        $this->assertEquals(
            $repository->getModel()->getBindings()[0],
            '%john%silver%',
        );
    }

    /**
     * @testdox Mysql - Having Filter ArrayInt
     */
    public function testMysqlFilterHavingArrayInt()
    {
        $repository = new RepositoryStub();
        $repository->havingArrayInt('name', ['1', '2']);
        $this->assertStringContainsString(
            ' having "name" in (?, ?)',
            $repository->getModel()->toSql(),
        );
        $this->assertSame($repository->getModel()->getBindings()[0], 1);
        $this->assertSame($repository->getModel()->getBindings()[1], 2);
    }

    /**
     * @testdox Mysql - Having Filter ArrayNotInt
     */
    public function testMysqlFilterHavingArrayNotInt()
    {
        $repository = new RepositoryStub();
        $repository->havingArrayNotInt('name', ['1', '2']);
        $this->assertStringContainsString(
            ' having "name" not in (?, ?)',
            $repository->getModel()->toSql(),
        );
        $this->assertSame($repository->getModel()->getBindings()[0], 1);
        $this->assertSame($repository->getModel()->getBindings()[1], 2);
    }

    /**
     * @testdox Mysql - Having Filter ArrayString
     */
    public function testMysqlFilterHavingArrayString()
    {
        $repository = new RepositoryStub();
        $repository->havingArrayString('name', ['1', '2']);
        $this->assertStringContainsString(
            ' having "name" in (?, ?)',
            $repository->getModel()->toSql(),
        );
        $this->assertSame($repository->getModel()->getBindings()[0], '1');
        $this->assertSame($repository->getModel()->getBindings()[1], '2');
    }

    /**
     * @testdox Mysql - Having Filter ArrayNotString
     */
    public function testMysqlFilterHavingArrayNotString()
    {
        $repository = new RepositoryStub();
        $repository->havingArrayNotString('name', ['1', '2']);
        $this->assertStringContainsString(
            ' having "name" not in (?, ?)',
            $repository->getModel()->toSql(),
        );
        $this->assertSame($repository->getModel()->getBindings()[0], '1');
        $this->assertSame($repository->getModel()->getBindings()[1], '2');
    }

    /**
     * @testdox Mysql - Having Filter Or IntGt
     */
    public function testMysqlFilterOrHavingIntGt()
    {
        $repository = new RepositoryStub();
        $repository->havingInt('age', 0);
        $repository->orHavingIntGt('id', 0);
        $this->assertStringContainsString(
            ' having "age" = ? or "id" > ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Having Filter Or IntLt
     */
    public function testMysqlFilterOrHavingIntLt()
    {
        $repository = new RepositoryStub();
        $repository->havingInt('age', 0);
        $repository->orHavingIntLt('id', 0);
        $this->assertStringContainsString(
            ' having "age" = ? or "id" < ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Having Filter Or IntGte
     */
    public function testMysqlFilterOrHavingIntGte()
    {
        $repository = new RepositoryStub();
        $repository->havingInt('age', 0);
        $repository->orHavingIntGte('id', 0);
        $this->assertStringContainsString(
            ' having "age" = ? or "id" >= ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Having Filter Or IntLte
     */
    public function testMysqlFilterOrHavingIntLte()
    {
        $repository = new RepositoryStub();
        $repository->havingInt('age', 0);
        $repository->orHavingIntLte('id', 0);
        $this->assertStringContainsString(
            ' having "age" = ? or "id" <= ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Having Filter Or SameString
     */
    public function testMysqlFilterOrHavingSameString()
    {
        $repository = new RepositoryStub();
        $repository->havingInt('age', 0);
        $repository->orHavingSameString('name', 'john silver');

        $this->assertStringContainsString(
            ' having "age" = ? or "name" = ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Having Filter Or Like
     */
    public function testMysqlFilterOrHavingLike()
    {
        $repository = new RepositoryStub();
        $repository->havingInt('age', 0);
        $repository->orHavingLike('name', 'john silver');
        $this->assertStringContainsString(
            ' having "age" = ? or "name" LIKE ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Having Filter Or NotLike
     */
    public function testMysqlFilterOrHavingNotLike()
    {
        $repository = new RepositoryStub();
        $repository->havingInt('age', 0);
        $repository->orHavingNotLike('name', 'john silver');
        $this->assertStringContainsString(
            ' having "age" = ? or "name" NOT LIKE ?',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Having Filter Or BeginWithLike
     */
    public function testMysqlFilterOrHavingBeginWithLike()
    {
        $repository = new RepositoryStub();
        $repository->havingInt('age', 0);
        $repository->orHavingBeginWithLike('name', 'john silver');
        $this->assertStringContainsString(
            ' having "age" = ? or "name" LIKE ?',
            $repository->getModel()->toSql(),
        );
        $this->assertEquals(
            $repository->getModel()->getBindings()[1],
            'john silver%',
        );
    }

    /**
     * @testdox Mysql - Having Filter Or EndWithLike
     */
    public function testMysqlFilterOrHavingEndWithLike()
    {
        $repository = new RepositoryStub();
        $repository->havingInt('age', 0);
        $repository->orHavingEndWithLike('name', 'john silver');
        $this->assertStringContainsString(
            ' having "age" = ? or "name" LIKE ?',
            $repository->getModel()->toSql(),
        );
        $this->assertEquals(
            $repository->getModel()->getBindings()[1],
            '%john silver',
        );
    }

    /**
     * @testdox Mysql - Having Filter Or ContainsLike
     */
    public function testMysqlFilterOrHavingContainsLike()
    {
        $repository = new RepositoryStub();
        $repository->havingInt('age', 0);
        $repository->orHavingContainsLike('name', 'john silver');
        $this->assertStringContainsString(
            ' having "age" = ? or "name" LIKE ?',
            $repository->getModel()->toSql(),
        );
        $this->assertEquals(
            $repository->getModel()->getBindings()[1],
            '%john silver%',
        );
    }

    /**
     * @testdox Mysql - Having Filter Or ContainsExplodeString
     */
    public function testMysqlFilterOrHavingContainsExplodeString()
    {
        $repository = new RepositoryStub();
        $repository->havingInt('age', 0);
        $repository->orHavingContainsExplodeString('name', 'john silver');
        $this->assertStringContainsString(
            ' having "age" = ? or "name" LIKE ?',
            $repository->getModel()->toSql(),
        );
        $this->assertEquals(
            $repository->getModel()->getBindings()[1],
            '%john%silver%',
        );
    }

    /**
     * @testdox Mysql - Having Filter Or ArrayInt
     */
    public function testMysqlFilterOrHavingArrayInt()
    {
        $repository = new RepositoryStub();
        $repository->havingInt('age', 0);
        $repository->orHavingArrayInt('name', ['1', '2']);
        $this->assertStringContainsString(
            ' having "age" = ? or "name" in (?, ?)',
            $repository->getModel()->toSql(),
        );
        $this->assertSame($repository->getModel()->getBindings()[1], 1);
        $this->assertSame($repository->getModel()->getBindings()[2], 2);
    }

    /**
     * @testdox Mysql - Having Filter Or ArrayNotInt
     */
    public function testMysqlFilterOrHavingArrayNotInt()
    {
        $repository = new RepositoryStub();
        $repository->havingInt('age', 0);
        $repository->orHavingArrayNotInt('name', ['1', '2']);
        $this->assertStringContainsString(
            ' having "age" = ? or "name" not in (?, ?)',
            $repository->getModel()->toSql(),
        );
        $this->assertSame($repository->getModel()->getBindings()[1], 1);
        $this->assertSame($repository->getModel()->getBindings()[2], 2);
    }

    /**
     * @testdox Mysql - Having Filter Or ArrayString
     */
    public function testMysqlFilterOrHavingArrayString()
    {
        $repository = new RepositoryStub();
        $repository->havingInt('age', 0);
        $repository->orHavingArrayString('name', ['1', '2']);
        $this->assertStringContainsString(
            ' having "age" = ? or "name" in (?, ?)',
            $repository->getModel()->toSql(),
        );
        $this->assertSame($repository->getModel()->getBindings()[1], '1');
        $this->assertSame($repository->getModel()->getBindings()[2], '2');
    }

    /**
     * @testdox Mysql - Having Filter Or ArrayNotString
     */
    public function testMysqlFilterOrHavingArrayNotString()
    {
        $repository = new RepositoryStub();
        $repository->havingInt('age', 0);
        $repository->orHavingArrayNotString('name', ['1', '2']);
        $this->assertStringContainsString(
            ' having "age" = ? or "name" not in (?, ?)',
            $repository->getModel()->toSql(),
        );
        $this->assertSame($repository->getModel()->getBindings()[1], '1');
        $this->assertSame($repository->getModel()->getBindings()[2], '2');
    }

    /**
     * @testdox Mysql - orderBy ASC
     */
    public function testMysqlFilterOrderByASC()
    {
        $repository = new RepositoryStub();
        $repository->orderBy('id', 'asc');

        $this->assertStringContainsString(
            ' order by "id" asc',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - orderBy DESC
     */
    public function testMysqlFilterOrderByDESC()
    {
        $repository = new RepositoryStub();
        $repository->orderBy('id', 'desc');

        $this->assertStringContainsString(
            ' order by "id" desc',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Limit
     */
    public function testMysqlLimit()
    {
        $repository = new RepositoryStub();
        $repository->limit(10);

        $this->assertStringContainsString(
            ' limit 10',
            $repository->getModel()->toSql(),
        );
    }

    /**
     * @testdox Mysql - Update
     */
    public function testMysqlUpdate()
    {
        // Salva query no logs
        DB::connection()->enableQueryLog();

        $repository = new RepositoryStub();
        $repository->update([
            'name' => 'hhh',
        ]);

        $this->assertStringContainsString(
            'update "model_repository_stubs" set ',
            DB::connection()->getQueryLog()[0]['query'],
        );
    }

    /**
     * @testdox Mysql - UpdateOne
     */
    public function testMysqlUpdateOne()
    {
        // Salva query no logs
        DB::connection()->enableQueryLog();

        $repository = new RepositoryStub();
        $repository->disableSoftDelete();
        $repository->updateOne([
            'sdsd.name' => 'name',
        ]);

        $this->assertStringContainsString(
            'update "model_repository_stubs" set "name" = ?, "updated_at" = ?',
            DB::connection()->getQueryLog()[0]['query'],
        );
    }

    /**
     * @testdox Mysql - FindById
     */
    public function testMysqlFindById()
    {
        // Salva query no logs
        DB::connection()->enableQueryLog();

        $repository = new RepositoryStub();
        $repository->findById(2);

        $this->assertStringContainsString(
            'where "model_repository_stubs"."id" = ? limit 1',
            DB::connection()->getQueryLog()[0]['query'],
        );
    }

    /**
     * @testdox Mysql - FindOne
     */
    public function testMysqlFindOne()
    {
        // Salva query no logs
        DB::connection()->enableQueryLog();

        $repository = new RepositoryStub();
        $repository->whereInt('id', 1)->findOne(false);

        $this->assertStringContainsString(
            'where "id" = ? limit 1',
            DB::connection()->getQueryLog()[0]['query'],
        );
    }

    /**
     * @testdox Mysql - FindMany
     */
    public function testMysqlFindMany()
    {
        // Salva query no logs
        DB::connection()->enableQueryLog();

        $repository = new RepositoryStub();
        $repository->whereInt('id', 1)->findMany();

        $this->assertStringContainsString(
            'where "id" = ? and "model_repository_stubs"."enabled" = ?',
            DB::connection()->getQueryLog()[0]['query'],
        );
    }

    /**
     * @testdox Mysql - Delete SoftDelete
     */
    public function testMysqlDeleteSoftDelete()
    {
        // Salva query no logs
        DB::connection()->enableQueryLog();

        $repository = new RepositoryStub();
        $repository->delete();

        $this->assertStringContainsString(
            'update "model_repository_stubs" set "enabled" = ?',
            DB::connection()->getQueryLog()[0]['query'],
        );
    }

    /**
     * @testdox Mysql - Delete
     */
    public function testMysqlDelete()
    {
        // Salva query no logs
        DB::connection()->enableQueryLog();

        $repository = new RepositoryStub();
        $repository->disableSoftDelete();
        $repository->whereInt('id', 1);
        $repository->delete();

        $this->assertStringContainsString(
            'delete from "model_repository_stubs" where "id" = ?',
            DB::connection()->getQueryLog()[0]['query'],
        );
    }

    /**
     * @testdox Mysql - deleteId
     */
    public function testMysqlDeleteId()
    {
        // Salva query no logs
        DB::connection()->enableQueryLog();

        $repository = new RepositoryStub();
        $repository->disableSoftDelete();
        $repository->whereInt('name', 1);
        $repository->deleteById(2);

        $this->assertStringContainsString(
            'delete from "model_repository_stubs" where "id" = ?',
            DB::connection()->getQueryLog()[0]['query'],
        );
    }
}
