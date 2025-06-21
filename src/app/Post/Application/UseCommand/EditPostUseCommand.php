<?php

namespace App\Post\Application\UseCommand;

use InvalidArgumentException;

class EditPostUseCommand
{
    public function __construct(
        private readonly int $id,
        private readonly int $userId,
        private readonly string $content,
        private readonly ?string $mediaPath,
        private readonly string $visibility
    ) {}

    private static $requiredProperties = [
        'id',
        'userId',
        'content',
        'visibility'
    ];

    private static function validate(array $data): void
    {
        foreach (self::$requiredProperties as $property) {
            if (!array_key_exists($property, $data)) {
                throw new InvalidArgumentException("Missing required property: {$property}");
            }
        }
    }

    public static function build(array $request): self
    {
        self::validate($request);

        return new self(
            id: $request['id'],
            userId: $request['userId'],
            content: $request['content'],
            mediaPath: $request['mediaPath'] ?? null,
            visibility: $request['visibility']
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'content' => $this->content,
            'mediaPath' => $this->mediaPath,
            'visibility' => $this->visibility
        ];
    }
}