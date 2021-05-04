import uuid4 from 'uuid/v4'
export function generateID() {
    return uuid4()
} 
    
export const generateModuleID = (type) => {
    return type.replace("module", "") + generateID()
}

export const recursifyID = (array) => {
    array.id = generateModuleID(array.type);
    if (!Array.isArray(array.content)) {
        return false
    }
    array.content.forEach((el) => {
        if (el.id || el.id === '') {
            el.id = generateModuleID(el.type);
        }
        if (!Array.isArray(el.content)) {
            return false
        } else {
            recursifyID(el)
        }
    })
}