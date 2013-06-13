<?php

namespace TNE\OperatorBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of AccommodationMediaTransformer
 *
 * @author zuhairnaqvi
 */
class AccommodationTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (accommodation) to a string (id).
     *
     * @param  accommodation|null $accommodation
     * @return string
     */
    public function transform($accommodation)
    {
        if (null === $accommodation) {
            return "";
        }

        return $accommodation->getId();
    }

    /**
     * Transforms a string (id) to an object (accommodation).
     *
     * @param  string $id
     *
     * @return accommodation|null
     *
     * @throws TransformationFailedException if object (accommodation) is not found.
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $accommodation = $this->om
            ->getRepository('TNEOperatorBundle:Accommodation')
            ->find($id)
        ;

        if (null === $accommodation) {
            throw new TransformationFailedException(sprintf(
                'An accommodation with ID "%s" does not exist!',
                $id
            ));
        }

        return $accommodation;
    }
}

?>
