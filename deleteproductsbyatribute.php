<?php
set_time_limit(0);
ini_set('display_errors', 1);
ini_set('memory_limit', -1);
use \Magento\Framework\App\Bootstrap;
require __DIR__ . '/app/bootstrap.php';
 
$bootstraps = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstraps->getObjectManager();
 
 
deleteAllProducts($objectManager);
 
function deleteAllProducts($objectManager) {
 
    $objectManager->get('\Magento\Framework\Registry')->register('isSecureArea', true);
 
    $attrSetName = 'here_your_atribute_set_name'; // Atribute set name
    $attribute_set_factoryCollection = $objectManager->get('\Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory');
 
    $attribute_set_collection = $attribute_set_factoryCollection->create();
 
    $attribute_set_collection
    ->addFieldToFilter('attribute_set_name',$attrSetName);
 
    $att_set = current($attribute_set_collection->getData());
    $attribute_set_id = $att_set["attribute_set_id"];
 
    $productCollection = $objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
    $collection = $productCollection->create()->addAttributeToSelect('*')->addFieldToFilter('attribute_set_id',$attribute_set_id)->load();
    $app_state = $objectManager->get('\Magento\Framework\App\State');
    $app_state->setAreaCode('frontend');
 
    foreach ($collection as $product){
        try {
            echo 'Deleted '.$product->getName().PHP_EOL;
            $product->delete();
        } catch (Exception $e) {
            echo 'Failed to remove product '.$product->getName() .PHP_EOL;
            echo $e->getMessage() . "n" .PHP_EOL;
        }
    }      
}
?>
