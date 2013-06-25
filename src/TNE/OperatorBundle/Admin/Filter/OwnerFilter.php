<?php
/**
 *
 * User: Muhammadali Shaduli
 * Date: 22/06/13
 * Time: 5:30 PM
 *
 */

namespace TNE\OperatorBundle\Admin\Filter;

use Symfony\Component\Security\Core\SecurityContext;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

class OwnerFilter
{
    /**
     * @var SecurityContext
     */
    private $securityContext;

    private $ownerFieldName = 'id';

    public function __construct(SecurityContext $securityContext, $ownerFieldName = null)
    {
        $this->securityContext = $securityContext;
        if ($ownerFieldName)
            $this->ownerFieldName = $ownerFieldName;
    }

    public function apply(ProxyQueryInterface $query)
    {
        $token = $this->securityContext->getToken();
        $user = $token->getUser();

        if ($user) {
            if (!($user instanceof \Application\Sonata\UserBundle\Entity\User)) {
                throw new \InvalidArgumentException(sprintf("User %s is not of type Application\Sonata\UserBundle\Entity\User", $user),0);
            }

            // Admins are allowed to view all
            if ($this->securityContext->isGranted('ROLE_SONATA_ADMIN'))
                return;

            $r = rand(100000, 999999);
            $alias = $query->getRootAlias();

            $paramName = $this->ownerFieldName . $r;
            $query->andWhere($query->expr()->eq(sprintf('%s.%s', $alias, $this->ownerFieldName ), ':'.$paramName));
            $query->setParameter($paramName, $user->getAccommodation());
        }
    }

    public function isOwner($object)
    {
        $token = $this->securityContext->getToken();
        $user = $token->getUser();
        if ($user) {
            if (!($user instanceof \Application\Sonata\UserBundle\Entity\User)) {
                throw new \InvalidArgumentException(sprintf("User %s is not of type Application\Sonata\UserBundle\Entity\User", $user),0);
            }

            if ($this->securityContext->isGranted('ROLE_SONATA_ADMIN'))
            {
                return true;
            }
            else
            {
                if($object->getId() == $user->getAccommodation()->getId())
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }

        }
    }
}

