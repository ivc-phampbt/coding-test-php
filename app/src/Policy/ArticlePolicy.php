<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Article;
use Authorization\IdentityInterface;
use Authorization\Policy\Result;

/**
 * Article policy
 */
class ArticlePolicy
{
    /**
     * Check if $user can add Article
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Article $article
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Article $article)
    {
        return true;
    }

    /**
     * Check if $user can edit Article
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Article $article
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Article $article): Result
    {
        $isAuthor = $this->isAuthor($user, $article);
        if ($isAuthor) {
            return new Result(true);
        }

        return new Result(false, 'Permission denied');
    }

    /**
     * Check if $user can delete Article
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Article $article
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Article $article): Result
    {
        $isAuthor = $this->isAuthor($user, $article);
        if ($isAuthor) {
            return new Result(true);
        }

        return new Result(false, 'Permission denied');
    }

    /**
     * Check if $user can view Article
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Article $article
     * @return bool
     */
    public function canView(IdentityInterface $user, Article $article)
    {
        return true;
    }

    protected function isAuthor(IdentityInterface $user, Article $article)
    {
        return $user->getIdentifier() === $article->user_id;
    }
}
