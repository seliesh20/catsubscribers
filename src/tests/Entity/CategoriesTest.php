<?php
// test/Entity/CategoriesTest.php
namespace App\Test\Entity;

use App\Entity\Categories;
use PHPUnit\Framework\TestCase;

class CategoriesTest extends TestCase
{
    public function testGetList()
    {
        $categories = new Categories();
        $result = $categories->getList();
        
        $categories_list = json_decode(file_get_contents('data/categories.json'));        
        $this->assertEquals(count($result), count($categories_list));        
    }

    public function testGetCategoryById()
    {
        $categories = new Categories();
        $result = $categories->getCategoryById(1);

        $this->assertEquals(is_array($result) && count($result), true);
    }
}