<?php
declare(strict_types=1);
namespace OM\FreeShippingProgressBar\Model\Source;

class CustomerGroups implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Magento\Customer\Api\GroupRepositoryInterface
     */
    protected \Magento\Customer\Api\GroupRepositoryInterface $_groupRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected \Magento\Framework\Api\SearchCriteriaBuilder $_searchCriteriaBuilder;

    /**
     * @param \Magento\Customer\Api\GroupRepositoryInterface $groupRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
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