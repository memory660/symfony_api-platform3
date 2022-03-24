<?php

namespace App\Security\Voters;

use App\Entity\Subject;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class SubjectVoter extends Voter
{
    public const DELETE = 'SUBJECT_DELETE';
    public const EDIT = 'SUBJECT_EDIT';
    public const VIEW = 'SUBJECT_VIEW';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::DELETE, self:: EDIT])
            && $subject instanceof \App\Entity\Subject;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // On vérifie si l'utilisateur est admin
        if($this->security->isGranted('ROLE_ADMIN')) return true;

        // On vérifie si l'annonce a un propriétaire
        if(null === $subject->getUser()) return false;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                // on vérifie si on peut éditer
                return $this->canEdit($subject, $user);
                break;            
            case self::DELETE:
                // logic to determine if the user can EDIT
                // return true or false
                // on vérifie si on peut éditer
                return $this->canDelete($subject, $user);                
                break;
        }

        return false;
    }

    private function canEdit(Subject $subject, User $user){
        // Le ROLE_EDITOR ou Le propriétaire du subject peut la modifier
        return $this->security->isGranted('ROLE_EDITOR') || $user === $subject->getUser();
    }

    private function canDelete(Subject $subject, User $user){
        // que Le propriétaire de subject peut la supprimer
        if($user === $subject->getUser()) return true;
        return false;
    }    
}
