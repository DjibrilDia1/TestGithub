<?php
use PHPUnit\Framework\TestCase;
use App\Database;
use App\Produit;
use App\StockManager;

class StockManagerTest extends TestCase
{
    protected function setUp(): void
    {
        Database::getTestConnection()->exec("DELETE FROM produits");
    }

    public function test_total_stock(): void
    {
        $manager = new StockManager();
        $manager->ajouterProduit(new Produit('Stylo', 1000, 2)); // 3.0
        $manager->ajouterProduit(new Produit('Cahier', 500, 4)); // 6.0
        $this->assertEquals(4000, $manager->totalStock());
    }
}
?>

