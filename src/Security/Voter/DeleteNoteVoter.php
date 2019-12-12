<?php

namespace App\Security\Voter;

use App\Entity\Note;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class DeleteNoteVoter extends Voter
{
    /** @var Security */
    private $security;

    /**
     * Le constructeur peut récupérer des services par autowiring
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Savoir si le voter doit intervenir
     * @param string     $attribute     L'attribut correspond au nom du droit (comme un role)
     * @param mixed      $subject       sur quoi cherche t-on a vérifier les droits
     *
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        // Faire intervenir le Voter seulement quand on vérifie le droit NOTE_DELETE
        // sur une Note
        // exemple: IsGranted("NOTE_DELETE", $note)
        return $attribute === 'NOTE_DELETE'
            && $subject instanceof Note;

        /*
         * opérateur instanceof: vérifier qu'un objet appartient à une classe
         * $objet instanceof Classe
         */
    }

    /**
     * Procéder à la vérification de l'accès
     * @param string            $attribute     l'attribut (ici "POST_DELETE")
     * @param mixed             $subject       le sujet du droit d'accès (ici l'instance de Note)
     * @param TokenInterface    $token         un jeton pour récupérer l'utilisateur actuel
     *
     * @return bool                     est-ce que l'utilisateur obtient le droit
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var Note $subject */

        // récupération de l'utilisateur grâce au jeton
        /** @var UserInterface $user */
        $user = $token->getUser();

        // Si l'utilisateur actuel n'est pas connecté: il n'a pas le droit d'accès
        if (!$user instanceof UserInterface) {
            return false;
        }

        // Si l'utilisateur est administrateur: accès accordé
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        // Si l'utilisateur est l'auteur de la note: accès accordé
        if ($subject->getUser() === $user) {
            return true;
        }

        // Sinon: accès refusé
        return false;
    }
}
