/**
 * Filament Real-time Updates for Game Availability
 *
 * This script handles real-time updates for the GameResource table
 * when game availability changes are broadcast via Reverb.
 */

// Wait for the page to load and ensure Echo is available
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.Echo === 'undefined') {
        console.warn('Laravel Echo not available for real-time updates');
        return;
    }

    // Listen for game availability changes
    window.Echo.channel('games')
        .listen('.game.availability.changed', (event) => {
            const gameId = event.id;
            const isAvailable = event.is_available;

            // Update Filament table toggles
            updateFilamentToggle(gameId, isAvailable);

            // Show notification
            showFilamentNotification(event);
        });
});

/**
 * Update the ToggleColumn in Filament table
 */
function updateFilamentToggle(gameId, isAvailable) {
    // Find the toggle element for this game
    const toggleElement = document.querySelector(`[data-game-id="${gameId}"] input[type="checkbox"]`);

    if (toggleElement) {
        // Update the toggle state
        toggleElement.checked = isAvailable;

        // Trigger change event to update any related UI elements
        toggleElement.dispatchEvent(new Event('change', { bubbles: true }));

        // Add visual feedback
        const toggleContainer = toggleElement.closest('[data-game-id]');
        if (toggleContainer) {
            toggleContainer.classList.add('realtime-updated');
            setTimeout(() => {
                toggleContainer.classList.remove('realtime-updated');
            }, 2000);
        }
    }
}

/**
 * Show Filament notification for availability changes
 */
function showFilamentNotification(event) {
    // Dispatch Livewire event for Filament notifications
    if (typeof window.Livewire !== 'undefined') {
        window.Livewire.dispatch('game-availability-changed', {
            game: event.name,
            available: event.is_available
        });
    }
}

// Add CSS for visual feedback
const style = document.createElement('style');
style.textContent = `
    .realtime-updated {
        animation: pulse-green 0.6s ease-in-out;
    }

    @keyframes pulse-green {
        0% { background-color: transparent; }
        50% { background-color: rgba(34, 197, 94, 0.1); }
        100% { background-color: transparent; }
    }
`;
document.head.appendChild(style);

