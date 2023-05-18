(function() {
    window.addEventListener('DOMContentLoaded', (event)=>{
        (function() {
            const nodes = document.querySelectorAll('input[data-submit-once="1"]');
            nodes.forEach((node)=>{
                node.addEventListener('click', (event)=>{
                    const el = event.target;
                    const form = el.closest('form');
                    if (form === null || !form.checkValidity()) {
                        return;
                    }
                    if (el.dataset.disabled && el.dataset.disabled === 1) {
                        event.preventDefault();
                        event.stopPropagation();
                        return false;
                    }
                    el.dataset.disabled = 1;
                });
            });
        })();
    });
})();
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImZpbGU6Ly8vVXNlcnMvc2Fub2tvdWhlaS9Eb2N1bWVudHMvUmVwb3NpdG9yeS9HaXRIdWIvYnVkZHlpbmctaW5jL2Ntcy9zb3VyY2UvYXBwL0J1ZGR5aW5nLkNtcy9wdWJsaWMvYWRtaW4tc3JjL2pzL21haW4udHMiXSwic291cmNlc0NvbnRlbnQiOlsiLy8gQHRzLWNoZWNrXG4vLy8gPHJlZmVyZW5jZSBsaWI9XCJkb21cIi8+XG5cbihmdW5jdGlvbiAoKSB7XG4gICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ0RPTUNvbnRlbnRMb2FkZWQnLCAoZXZlbnQpID0+IHtcbiAgICAgICAgKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIC8vIFN0b3AgZHVwbGljYXRlIGNsaWNrc1xuICAgICAgICAgICAgY29uc3Qgbm9kZXMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKCdpbnB1dFtkYXRhLXN1Ym1pdC1vbmNlPVwiMVwiXScpO1xuICAgICAgICAgICAgbm9kZXMuZm9yRWFjaCgobm9kZSkgPT4ge1xuICAgICAgICAgICAgICAgIG5vZGUuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCAoZXZlbnQpID0+IHtcbiAgICAgICAgICAgICAgICAgICAgY29uc3QgZWwgPSBldmVudC50YXJnZXQ7XG5cbiAgICAgICAgICAgICAgICAgICAgY29uc3QgZm9ybSA9IGVsLmNsb3Nlc3QoJ2Zvcm0nKTtcbiAgICAgICAgICAgICAgICAgICAgaWYgKGZvcm0gPT09IG51bGwgfHwgISBmb3JtLmNoZWNrVmFsaWRpdHkoKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgaWYgKGVsLmRhdGFzZXQuZGlzYWJsZWQgJiYgZWwuZGF0YXNldC5kaXNhYmxlZCA9PT0gMSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGV2ZW50LnN0b3BQcm9wYWdhdGlvbigpO1xuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgZWwuZGF0YXNldC5kaXNhYmxlZCA9IDE7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSkoKTtcbiAgICB9KTtcbn0pKCk7XG4iXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBR0EsQ0FBQyxXQUFZO0lBQ1QsT0FBTyxnQkFBZ0IsQ0FBQyxvQkFBb0IsQ0FBQyxRQUFVO1FBQ25ELENBQUMsV0FBWTtZQUVULE1BQU0sUUFBUSxTQUFTLGdCQUFnQixDQUFDO1lBQ3hDLE1BQU0sT0FBTyxDQUFDLENBQUMsT0FBUztnQkFDcEIsS0FBSyxnQkFBZ0IsQ0FBQyxTQUFTLENBQUMsUUFBVTtvQkFDdEMsTUFBTSxLQUFLLE1BQU0sTUFBTTtvQkFFdkIsTUFBTSxPQUFPLEdBQUcsT0FBTyxDQUFDO29CQUN4QixJQUFJLFNBQVMsSUFBSSxJQUFJLENBQUUsS0FBSyxhQUFhLElBQUk7d0JBQ3pDO29CQUNKLENBQUM7b0JBRUQsSUFBSSxHQUFHLE9BQU8sQ0FBQyxRQUFRLElBQUksR0FBRyxPQUFPLENBQUMsUUFBUSxLQUFLLEdBQUc7d0JBQ2xELE1BQU0sY0FBYzt3QkFDcEIsTUFBTSxlQUFlO3dCQUNyQixPQUFPLEtBQUs7b0JBQ2hCLENBQUM7b0JBRUQsR0FBRyxPQUFPLENBQUMsUUFBUSxHQUFHO2dCQUMxQjtZQUNKO1FBQ0osQ0FBQztJQUNMO0FBQ0osQ0FBQyJ9
