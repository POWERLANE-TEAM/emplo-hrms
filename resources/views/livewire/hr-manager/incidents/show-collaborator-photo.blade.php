<div>
    <style>
        .collaborator-photo {
            margin-left: -8px;
            z-index: 1;
            position: relative;
        }
    
        .collaborator-photo img {
            border: 2px solid white;
        }
    
        .collaborator-photo:first-child {
            margin-left: 0;
        }
    
        .more-photos {
            background-color: #6c757d;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.9rem;
            font-weight: bold;
            border: 2px solid white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            z-index: 10;
        }
    </style>

    @php $collaboratorCount = 0; @endphp
    
    <div class="d-flex align-items-center mt-2 justify-content-center">
        @foreach ($collaborators as $index => $collaborator)
            @if ($index === 1)
                @foreach ($collaborator as $authority)
                    @if ($collaboratorCount < 7)
                        <div class="collaborator-photo" wire:key="{{ $authority->user_id }}">
                            <img src="{{ $authority->photo }}" width="30" height="30" class="rounded-circle" alt="Collaborator photo">   
                        </div>                        
                    @endif
                    @php $collaboratorCount++ @endphp
                @endforeach
            @else
                @if ($collaboratorCount < 7)
                    <div class="collaborator-photo" wire:key="{{ $collaborator->employee_id }}">
                        <img src="{{ $collaborator->account->photo }}" width="30" height="30" class="rounded-circle" alt="Collaborator photo">      
                    </div>
                @endif
                @php $collaboratorCount++ @endphp
            @endif
        @endforeach
        
        @if ($collaboratorCount > 7)
            <div class="collaborator-photo more-photos">
                +{{ $collaboratorCount - 7 }}
            </div>
        @endif
    </div>
</div>
