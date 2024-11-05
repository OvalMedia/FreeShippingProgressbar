<?php
declare(strict_types=1);
namespace OM\FreeShippingProgressBar\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class CustomerGroups implements OptionSourceInterface
{
    /**
     * @var \Magento\Customer\Api\GroupRepositoryInterface
     */
    protected GroupRepositoryInterface $_groupRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected SearchCriteriaBuilder $_searchCriteriaBuilder;

    /**
     * @param \Magento\Customer\Api\GroupRepositoryInterface $groupRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        GroupRepositoryInterface $groupRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->_groupRepository = $groupRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $searchCriteria = $this->_searchCriteriaBuilder->create();
        $customerGroups = $this->_groupRepository->getList($searchCriteria)->getItems();

        $groups = [];

        foreach ($customerGroups as $group) {
            $groups[] = [
                'value' => $group->getId(),
                'label' => $group->getCode()
            ];
        }

        return $groups;
    }
}